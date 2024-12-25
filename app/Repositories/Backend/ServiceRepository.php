<?php

namespace App\Repositories\Backend;

use App\Enums\CategoryType;
use App\Enums\RoleEnum;
use App\Enums\ServiceTypeEnum;
use App\Helpers\Helpers;
use App\Models\Address;
use App\Models\Category;
use App\Models\Zone;
use App\Models\Service;
use App\Models\Tax;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Prettus\Repository\Eloquent\BaseRepository;

class ServiceRepository extends BaseRepository
{
    protected $address;

    protected $providers;

    protected $category;

    protected $taxes;

    public function model()
    {
        $this->category = new Category();
        $this->providers = new User();
        $this->address = new Address();
        $this->taxes = new Tax();
        $this->zone = new Zone();
 
        return Service::class;
    }

    public function index()
    {
        return view('backend.service.index');
    }

    public function create($attributes = [])
    {
        // $dropdownOptions = $this->category->getDropdownOptions();
      
        return view('backend.service.create', [
            'services' => $this->getServices('service'),
            'providers' => $this->getProviders(),
            'categories' => [],
            'countries' => Helpers::getCountries(),
            'taxes' => $this->getTaxes(),
            'zones' => $this->getZones()
        ]);
    }

    public function getTaxes()
    {
        return $this->taxes->where('status', true)->pluck('name', 'id');
    }
    public function getZones(){
        return $this->zone->where('status',true)->pluck('name','id');
    }

    public function isProviderCanCreate()
    {
        if (Helpers::isUserLogin()) {
            $isAllowed = true;
            $roleName = Helpers::getCurrentRoleName();
            if ($roleName == RoleEnum::PROVIDER) {
                $isAllowed = false;
                $provider = Auth::user();
                $maxItems = $provider?->services()->count();
                if (Helpers::isModuleEnable('Subscription')) {
                    if (function_exists('isPlanAllowed')) {
                        $isAllowed = isPlanAllowed('allowed_max_services', $maxItems, $provider?->id);
                    }
                }

                if (! $isAllowed) {
                    $settings = Helpers::getSettings();
                    $max_services = $settings['default_creation_limits']['allowed_max_services'];
                    if ($max_services > $maxItems) {
                        $isAllowed = true;
                    }
                }
            }

            return $isAllowed;
        }
    }

    public function store($request)
    {

        DB::beginTransaction();
        try {
            
            if ($this->isProviderCanCreate()) {
                $service = $this->model->create([
                    'type' => $request->type,
                    'price' => $request->price,
                    'title' => $request->title,
                    'status' => $request->status,
                    'discount' => $request->discount,
                    'per_serviceman_commission' => $request->per_serviceman_commission,
                    'duration' => $request->duration,
                    'duration_unit' => $request->duration_unit,
                    'user_id' => $request->user_id,
                    'description' => $request->description,
                    'content' => $request->content,
                    'is_featured' => $request->is_featured,
                    'required_servicemen' => $request->required_servicemen,
                    'tax_id' => $request->tax_id,
                    'service_rate' => $request->service_rate,
                    'isMultipleServiceman' => $request->isMultipleServiceman,
                    'is_random_related_services' => $request->is_random_related_services,
                ]);

                if (isset($request->category_id)) {
                    $service->categories()->attach($request->category_id);
                    $service->categories;
                }

                if (!isset($request->service_id) && $request->is_random_related_services == true) {
                    $rand_service_id = $request->category_id[array_rand($request->category_id)];
                    $related_service_ids = Helpers::getRelatedServiceId($service, $rand_service_id, $service->id);
                    $service->related_services()->attach($related_service_ids);
                }

                if (isset($request->service_id) && $request->is_random_related_services == false) {
                    $service->related_services()->attach($request->service_id);
                }

                if ($request->hasFile('image')) {
                    $images = $request->file('image');
                    foreach ($images as $image) {
                        $service->addMedia($image)->toMediaCollection('image');
                    }
                    $service->media;
                }

                if ($request->hasFile('web_images')) {
                    $images = $request->file('web_images');
                    foreach ($images as $image) {
                        $service->addMedia($image)->toMediaCollection('web_images');
                    }
                    $service->media;
                }

                if ($request->hasFile('web_thumbnail') && $request->file('web_thumbnail')->isValid()) {
                    $service->addMedia($request->file('web_thumbnail'))->toMediaCollection('web_thumbnail');
                }

                if ($request->hasFile('thumbnail') && $request->file('thumbnail')->isValid()) {
                    $service->addMedia($request->file('thumbnail'))->toMediaCollection('thumbnail');
                }

                // Store FAQs
                if (isset($request->faqs) && is_array($request->faqs)) {
                    foreach ($request->faqs as $faq) {
                        $service->faqs()->create([
                            'question' => $faq['question'],
                            'answer' => $faq['answer'],
                        ]);
                    }
                }

                if($request->type == ServiceTypeEnum::PROVIDER_SITE){
                    $providerSiteaddress = [
                        'country_id' => $request->country_id,
                        'state_id' => $request->state_id,
                        'city' => $request->city,
                        'area' => $request->area,
                        'postal_code' => $request->postal_code,
                        'address' => $request->address,
                        'lat' => (float) $request->lat,
                        'lng' => (float) $request->lng
                    ];
                    $destinationLocation = $providerSiteaddress;
                    $service->destination_location = $destinationLocation;
                    $service->save();
                }
                else{
                    $address = $this->address->create([
                        'service_id' => $service->id,
                        'type' => $request->address_type,
                        'alternative_name' => $request->alternative_name,
                        'alternative_phone' => $request->alternative_phone,
                        'code' => $request->alternative_code,
                        'country_id' => $request->country_id,
                        'state_id' => $request->state_id,
                        'city' => $request->city,
                        'area' => $request->area,
                        'postal_code' => $request->postal_code,
                        'address' => $request->address,
                    ]);
                }

                DB::commit();

                return redirect()->route('backend.service.index')->with('message', __('static.service.store'));
            }

            throw new Exception(__('static.not_allow_for_creation'), 400);
        } catch (Exception $e) {

            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $service = $this->model->findOrFail($id);
        $categories = $service->categories;
        $zones = $categories->flatMap(function ($category) {
            return $category->zones->where('status',true); 
        })->pluck('id','name'); 

        
        return view('backend.service.edit', [
            'service' => $service,
            'taxes' => $this->getTaxes(),
            'providers' => $this->getProviders(),
            // 'categories' => $this->getCategories(),
            'countries' => Helpers::getCountries(),
            'services' => $this->getServices($service),
            'default_categories' => $this->getDefaultCategories($service),
            'zones' => $this->getZones(),
            'selected_zones' => $zones->toArray() 
        ]); 
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $service = $this->model->findOrFail($id);
            if($service->per_serviceman_commission !== $request->per_serviceman_commission && Helpers::getCurrentRoleName() !== RoleEnum::PROVIDER){
                $this->sendPushNotification($request, $id);
            }
            $service->update($request->all());
            if($request->type == ServiceTypeEnum::PROVIDER_SITE && isset($request->location)){
                $service->destination_location = $request->location;
            }
            $service->save;

            if (isset($request->category_id)) {
                $service->categories()->sync($request['category_id']);
            }

            if (!isset($request->service_id) && $request->is_random_related_services == true) {
                $rand_service_id = $request->category_id[array_rand($request->category_id)];
                $related_service_ids = Helpers::getRelatedServiceId($service, $rand_service_id, $service->id);
                $service->related_services()->sync($related_service_ids);
            }

            if (isset($request->service_id) && $request->is_random_related_services == false) {
                $service->related_services()->sync($request->service_id);
            }

            if ($request->image) {
                $uploadedImages = $request->image;
                $service->clearMediaCollection('image');
                foreach ($uploadedImages as $uploadedImage) {
                    if ($uploadedImage->isValid()) {
                        $service->addMedia($uploadedImage)->toMediaCollection('image');
                    }
                    $service->media;
                }
            }

            if ($request['thumbnail']) {
                $service->clearMediaCollection('thumbnail');
                $service->addMedia($request['thumbnail'])->toMediaCollection('thumbnail');
            }

            if ($request->web_images) {
                $uploadedImages = $request->web_images;
                $service->clearMediaCollection('web_images');
                foreach ($uploadedImages as $uploadedImage) {
                    if ($uploadedImage->isValid()) {
                        $service->addMedia($uploadedImage)->toMediaCollection('web_images');
                    }
                    $service->media;
                }
            }

            if ($request['web_thumbnail']) {
                $service->clearMediaCollection('web_thumbnail');
                $service->addMedia($request['web_thumbnail'])->toMediaCollection('web_thumbnail');
            }

            // Update FAQs
            if (isset($request->faqs) && is_array($request->faqs)) {
                $requestFaqIds = array_filter(array_column($request->faqs, 'id'));
                foreach ($request->faqs as $faq) {
                    if (isset($faq['id'])) {
                        // Update existing FAQ
                        $existingFaq = $service->faqs()->where('id', $faq['id'])->first();
                        if ($existingFaq) {
                            $existingFaq->update([
                                'question' => $faq['question'],
                                'answer' => $faq['answer'],
                            ]);
                        }
                    } else {
                        // Create new FAQ
                        $requestFaqIds[] = $service->faqs()->create([
                            'question' => $faq['question'],
                            'answer' => $faq['answer'],
                        ])->id;
                    }
                }
                $service->faqs()->whereNotIn('id', $requestFaqIds)->delete();
            }

            DB::commit();

            return redirect()->route('backend.service.index')->with('message', __('static.service.updated'));
        } catch (Exception $e) {

            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function sendPushNotification($request,$id){
       
        $token = $this->providers->where('id', $request->user_id)->pluck('fcm_token')->first();
        if ($token) {
            $notification = [
                'message' => [
                    'token' => $token,
                    'notification' => [
                        'title' => 'Commission Rate Changed',
                        'body' => 'The commission rate has been updated. Please check your dashboard for details.',
                    ],
                    'data' => [
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        'type' => 'service',
                        'service_id' => $id
                    ],
                ],
            ];
            
            Helpers::pushNotification($notification);
        }
    }
    public function status($id, $status)
    {
        try {
            $service = $this->model->findOrFail($id);
            $service->update(['status' => $status]);

            return json_encode(['resp' => $service]);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {

            $service = $this->model->findOrFail($id);
            $service->destroy($id);

            return redirect()->route('backend.service.index')->with('message', 'Service Deleted Successfully');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    protected function getProviders()
    {
        return $this->providers->role('provider')->where('status', true)->get();
    }

    private function getCategories()
    {
        return $this->category->where('category_type', CategoryType::SERVICE)
            ->where('status', true)
            ->pluck('title', 'id');
    }

    private function getDefaultCategories($service)
    {
        $categories = [];
        foreach ($service->categories as $category) {
            $categories[] = $category->id;
        }
        $categories = array_map('strval', $categories);

        return $categories;
    }

    private function getServices($service)
    {
        if (Request::is('backend/service/create')) {
            return $this->model->get();
        } else {
            return $this->model->get()->except($service->id);
        }
    }

    public function getZoneCategories($request){
        $categories = $this->category->getDropdownOptions($request->zone_id);
        return response()->json($categories);
    }
} 
