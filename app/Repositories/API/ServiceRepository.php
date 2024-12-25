<?php

namespace App\Repositories\API;

use App\Enums\RoleEnum;
use App\Exceptions\ExceptionHandler;
use App\Helpers\Helpers;
use App\Models\Service;
use App\Models\ServiceFAQ;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Events\CreateServiceEvent;

class ServiceRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'title' => 'like',
    ];

    protected $serviceFAQS;

    public function boot()
    {
        try {

            $this->pushCriteria(app(RequestCriteria::class));
        } catch (ExceptionHandler $e) {

            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    public function model()
    {
        $this->serviceFAQS = new ServiceFAQ();

        return Service::class;
    }

    public function isProviderCanCreate()
    {
        if (Helpers::isUserLogin()) {
            $isAllowed = true;
            $roleName = Helpers::getCurrentRoleName();
            if ($roleName == RoleEnum::PROVIDER) {
                $isAllowed = false;
                $provider = Auth::user();
                $maxItems = $provider?->services()?->count() ?? 0;
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
                $service_rate = $request->price - ($request->price * $request->discount / 100);
                $service = $this->model->create([
                    'type' => $request->type,
                    'price' => $request->price,
                    'title' => $request->title,
                    'status' => $request->status,
                    'discount' => $request->discount,
                    'per_serviceman_commission' => $request->per_serviceman_commission,
                    'duration' => $request->duration,
                    'user_id' => $request->provider_id ?? auth('api')->user()->id,
                    'meta_title' => $request->meta_title ?? null,
                    'description' => $request->description ?? null,
                    'speciality_description' => $request->speciality_description ?? null,
                    'is_featured' => $request->is_featured,
                    'tax_id' => $request->tax_id,
                    'duration_unit' => $request->duration_unit,
                    'service_rate' => $service_rate,
                    'isMultipleServiceman' => $request->isMultipleServiceman ?? 0,
                    'required_servicemen' => $request->required_servicemen,
                    'meta_description' => $request->meta_description,
                    'created_by_id' => auth('api')->user()?->id,
                    'destination_location' => $request->destination_location
                ]);

                if (isset($request->category_id)) {
                    $service->categories()->attach($request->category_id);
                    $service->categories;
                }

                if (! isset($request->service_id) && $request->is_random_related_services == true) {
                    $rand_service_id = $request->category_id[array_rand($request->category_id)];
                    $related_service_ids = Helpers::getRelatedServiceId($service, $rand_service_id, $service->id);
                    $service->related_services()->attach($related_service_ids);
                }

                if (isset($request->service_id) && $request->is_random_related_services == false) {
                    $service->related_services()->attach($request->service_id);
                }

                if ($request->image) {
                    $images = $request->file('image');
                    foreach ($images as $image) {
                        $service->addMedia($image)->toMediaCollection('image');
                    }
                    $service->media;
                }

                if (isset($request->thumbnail) || $request->hasFile('thumbnail')) {
                    $service->addMedia($request->thumbnail)->toMediaCollection('thumbnail');
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

                DB::commit();
                event(new CreateServiceEvent($service));
                return response()->json([
                    'message' => __('static.service.service_created_sucessfully'),
                    'service' => $service,
                ]);

            }

            throw new Exception(__('static.not_allow_for_creation'), 400);
        } catch (Exception $e) {
            DB::rollback();
            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {

            $service = $this->model->findOrFail($id);
            if (isset($request['price'])) {
                $service_rate = $request['price'] - ($request['price'] * $request['discount'] / 100);
            }

            $req = $request;
            unset($req['address_id']);

            if (isset($request['destination_location'])) {
                $destination_location = [
                    'lat' => (float) $request['destination_location']['lat'],
                    'lng' => (float) $request['destination_location']['lng'],
                    'area' => $request['destination_location']['area'],
                    'address' => $request['destination_location']['address'],
                    'state_id' => $request['destination_location']['state_id'],
                    'country_id' => $request['destination_location']['country_id'],
                    'postal_code' => $request['destination_location']['postal_code'],
                    'city' => $request['destination_location']['city'],
                ];
                $req['destination_location'] = $destination_location;
            }
            $service->update($req);

            if (isset($request['category_id'])) {
                $service->categories()->sync($request['category_id']);
                $service->categories;
            }

            if (!isset($request['service_id']) && isset($request['is_random_related_services']) == true) {
                $rand_service_id = $request['category_id'][array_rand($request['category_id'])];
                $related_service_ids = Helpers::getRelatedServiceId($service, $rand_service_id, $service->id);
                $service->related_services()->sync($related_service_ids);
            }

            if (isset($request['service_id']) && isset($request['is_random_related_services']) == false) {
                $service->related_services()->sync($request['service_id']);
            }

            if (isset($request['image'])) {
                $images = $request['image'];
                $service->clearMediaCollection('image');
                foreach ($images as $image) {
                    $service->addMedia($image)->toMediaCollection('image');
                }
                $service->media;
            }

            if (isset($request['thumbnail'])) {
                $service->addMedia($request['thumbnail'])->toMediaCollection('thumbnail');
            }

            if (isset($request['faqs']) && is_array($request['faqs'])) {
                $requestFaqIds = array_filter(array_column($request['faqs'], 'id'));
                foreach ($request['faqs'] as $faq) {
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

            return response()->json([
                'message' => __('static.service.updated'),
                'service' => $service,
            ]);
        } catch (Exception $e) {
            DB::rollback();
            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $service = $this->model->findOrFail($id);
            if ($service) {
                $service->delete();

                return response()->json([
                    'success' => true,
                    'message' => __('static.service.destroy'),
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => __('static.service.service_not_found'),
                ]);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function storeServiceAddresses($request, $id)
    {
        DB::beginTransaction();
        try {
            $service = $this->model::findOrFail($id);
            if ($service) {
                if (isset($request->address_ids)) {
                    foreach ($request->address_ids as $addressId) {
                        $service->serviceAvailabilities()->create(['address_id' => $addressId]);
                    }
                }
                DB::commit();

                return response()->json([
                    'message' => __('static.service.service_address_store'),
                    'service' => $service,
                ]);
            } else {
                throw new Exception(__('static.service.invalid_service_id'), 404);
            }
        } catch (Exception $e) {
            DB::rollback();
            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    public function deleteServiceAddresses($id, $address_id)
    {
        DB::beginTransaction();
        try {
            $service = Service::findOrFail($id);
            if ($service) {
                $service_address = $service->serviceAvailabilities()
                    ->where('service_id', $service->id)
                    ->where('id', $address_id)
                    ->first();
                $service_address->delete();
                DB::commit();

                return response()->json([
                    'message' => __('static.service.service_address_destroy'),
                    'service' => $service,
                ]);
            } else {
                throw new ExceptionHandler(__('static.service.invalid_service_id'), 404);
            }
        } catch (Exception $e) {
            DB::rollback();
            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    public function serviceFAQS($request)
    {
        $service = $this->model::findOrFail($request->service_id);

        return $service->faqs;
    }
}
