<?php

namespace App\Repositories\Backend;

use Exception;
use App\Enums\RoleEnum;
use App\Helpers\Helpers;
use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Prettus\Repository\Eloquent\BaseRepository;
use Spatie\Permission\Models\Role;

class ServicemanRepository extends BaseRepository
{
    protected $role;

    protected $address;

    public function model()
    {
        $this->address = new Address();
        $this->role = new Role();
        return User::class;
    }

    public function show($id)
    {
        try {
            return $this->model->with('permissions')->findOrFail($id);
        } catch (Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }

    public function isProviderCanCreate()
    {
        if (Helpers::isUserLogin()) {
            $isAllowed = true;
            $roleName = Helpers::getCurrentRoleName();
            if ($roleName == RoleEnum::PROVIDER) {
                $isAllowed = false;
                $provider = Auth::user();
                $maxItems = $provider?->servicemans()->count();
                if (Helpers::isModuleEnable('Subscription')) {
                    if (function_exists('isPlanAllowed')) {
                        $isAllowed = isPlanAllowed('allowed_max_servicemen', $maxItems, $provider?->id);
                    }
                }

                if (! $isAllowed) {
                    $settings = Helpers::getSettings();
                    $max_serviceman = $settings['default_creation_limits']['allowed_max_servicemen'];
                    if ($max_serviceman > $maxItems) {
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
                $serviceman = $this->model->create([
                    'provider_id' => $request->provider_id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'code' => $request->code,
                    'phone' => $request->phone,
                    'is_featured' => $request->is_featured,
                    'status' => $request->status,
                    'password' => Hash::make($request->password),
                    'experience_interval' => $request->experience_interval,
                    'experience_duration' => $request->experience_duration,
                    'description' => $request->description,
                ]);

                if ($request->hasFile('image') && $request->file('image')->isValid()) {
                    $serviceman->addMediaFromRequest('image')->toMediaCollection('image');
                }

                $role = $this->role->where('name', RoleEnum::SERVICEMAN)->first();
                if ($request->role) {
                    $role = $this->role->findOrFail($request->role);
                }

                $serviceman->assignRole($role);
                if (isset($request->known_languages)) {
                    $serviceman->knownLanguages()->attach($request->known_languages);
                    $serviceman->knownLanguages;
                }

                $address = $this->address->create([
                    'user_id' => $serviceman->id,
                    'type' => $request->address_type,
                    'alternative_name' => $request->alternative_name,
                    'code' => $request->alternative_code,
                    'alternative_phone' => $request->alternative_phone,
                    'area' => $request->area,
                    'postal_code' => $request->postal_code,
                    'country_id' => $request->country_id,
                    'state_id' => $request->state_id,
                    'city' => $request->city,
                    'address' => $request->address,
                    'is_primary' => true,
                ]);

                DB::commit();

                return redirect()->route('backend.serviceman.index')->with('message', 'Serviceman Created Successfully.');

            }

            throw new Exception(__('static.not_allow_for_creation'), 400);
        } catch (Exception $e) {

            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $serviceman = $this->model->findOrFail($id);
            if ($serviceman->system_reserve) {
                return redirect()->route('backend.user.index')->with('error', 'This User Cannot be Update. It is System reserved.');
            }
            $serviceman->update($request->except(['_token', '_method', 'submit']));

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $serviceman->clearMediaCollection('image');
                $serviceman->addMediaFromRequest('image')->toMediaCollection('image');
            }
            $role = $this->role->where('name', RoleEnum::SERVICEMAN)->first();
            $serviceman->syncRoles($role);

            if (isset($request['known_languages'])) {
                $serviceman->knownLanguages()->sync($request['known_languages']);
            }

            $address = $this->address->where('user_id', $serviceman->id)->where('is_primary', true)->first();
            $address->update([
                'user_id' => $serviceman->id,
                'type' => $request['address_type'],
                'alternative_name' => $request['alternative_name'],
                'code' => $request['alternative_code'],
                'alternative_phone' => $request['alternative_phone'],
                'area' => $request['area'],
                'postal_code' => $request['postal_code'],
                'country_id' => $request['country_id'],
                'state_id' => $request['state_id'],
                'city' => $request['city'],
                'address' => $request['address'],
            ]);

            DB::commit();

            return redirect()->route('backend.serviceman.index')->with('message', 'Serviceman Updated Successfully.');
        } catch (Exception $e) {

            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $serviceman = $this->model->findOrFail($id);
            if ($serviceman->hasRole(RoleEnum::ADMIN)) {
                return redirect()->route('backend.role.index')->with('error', 'System reserved.');
            }
            $serviceman->forcedelete($id);

            DB::commit();
            return redirect()->route('backend.serviceman.index')->with('message', 'Serviceman Deleted Successfully');
        } catch (Exception $e) {

            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function updateIsFeatured($isFeatured, $subjectId)
    {
        DB::beginTransaction();
        try {
            $category = $this->model->findOrFail($subjectId);
            $category->is_featured = $isFeatured;
            $category->save();

            DB::commit();

            return redirect()->route('backend.serviceman.index')->with('message', 'Is Featured Updated Successfully');
        } catch (Exception $e) {

            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }

    public function updateStatus($statusVal, $subjectId)
    {
        DB::beginTransaction();
        try {

            $user = $this->model->findOrFail($request->userId);
            $user->update([
                'status' => $request->status,
            ]);
            DB::commit();

            return;
        } catch (Exception $e) {

            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }

    public function changePassword($request, $id)
    {
        DB::beginTransaction();
        try {

            $serviceman = $this->model->findOrFail($id);
            $serviceman->update(['password' => Hash::make($request->new_password)]);

            DB::commit();

            return redirect()->route('backend.serviceman.index')->with('message', 'Password Updated Successfully');
        } catch (Exception $e) {

            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }

    public function deleteAll($ids)
    {
        DB::beginTransaction();
        try {

            $this->model->whereNot('system_reserve', true)->whereIn('id', $ids)->delete();

            return back()->with('message', 'Roles Deleted Successfully');
        } catch (Exception $e) {

            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }

    public function status($id, $status)
    {
        try {

            $serviceman = $this->model->findOrFail($id);
            $serviceman->update(['status' => $status]);

            return json_encode(['resp' => $serviceman]);
        } catch (Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }

    public function servicemanLocation()
    {
        try {
            $roleName = auth('web')->user()->roles->pluck('name')->first();
            $servicemen = $this->model->role(RoleEnum::SERVICEMAN)->whereNotNull('location_cordinates');
            
            if ($roleName == RoleEnum::PROVIDER) {
                $servicemen = $servicemen->where('provider_id', auth('web')->user()->id);
            }

            $servicemenData = $servicemen->get()->map(function ($serviceman) {
                $locationData = json_decode($serviceman->location_cordinates);
                return [
                    'id' => $serviceman->id,
                    'name' => $serviceman->name,
                    'email' => $serviceman->email,
                    'phone' => $serviceman->phone,
                    'vehicle_name' => $serviceman->vehicle_info?->vehicle?->name,
                    'vehicle_image' => asset('admin/images/user.png'),
                    'image' => $serviceman->getFirstMedia('image')?->getUrl() ?? asset('admin/images/user.png'),
                    'review' => $serviceman->servicemanreviews?->avg('rating'),
                    'lat' => $locationData->lat ?? null,
                    'lng' => $locationData->lng ?? null,
                ];
            });
    
            return view('backend.serviceman-location.index', [
                'servicemen' => $servicemenData, 
            ]);
        } catch (Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }

    public function servicemanCordinates($id)
    {
        try {
        $serviceman = $this->model->findOrFail($id);
        
        $location = json_decode($serviceman->location_cordinates);
        if ($location) {
            return response()->json([
                'id' => $serviceman->id,
                'name' => $serviceman->name,
                'email' => $serviceman->email,
                'phone' => $serviceman->phone,
                'image' => $serviceman->getFirstMedia('image')?->getUrl() ?? asset('admin/images/user.png'),
                'review' => $serviceman->servicemanreviews?->avg('rating'),
                'lat' => $location->lat,
                'lng' => $location->lng,
            ]);
        }

        return response()->json(['error' => 'Location not found'], 404);

        } catch (Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }
}
