<?php

namespace App\Repositories\Backend;

use App\Enums\RoleEnum;
use App\Enums\UserTypeEnum;
use App\Events\CreateProviderEvent;
use App\Helpers\Helpers;
use App\Models\Address;
use App\Models\BankDetail;
use App\Models\Company;
use App\Models\Language;
use App\Models\Service;
use App\Models\User;
use App\Models\Zone;
use App\Models\Booking;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Prettus\Repository\Eloquent\BaseRepository;
use Spatie\Permission\Models\Role;

class ProviderRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name' => 'like',
    ];

    protected $role;

    protected $company;

    protected $address;

    protected $language;

    protected $service;

    protected $bankDetail;

    protected $zone;

    public function model()
    {
        $this->company = new Company();
        $this->address = new Address();
        $this->role = new Role();
        $this->language = new Language();
        $this->service = new Service();
        $this->bankDetail = new BankDetail();
        $this->zone = new Zone();

        return User::class;
    }

    public function index()
    {
        return view('backend.provider.index', [
            'providers' => $this->getProvidersWithAddress(),
        ]);
    }

    public function create($attribute = [])
    {
        $languages = $this->language->get();

        return view('backend.provider.create', [
            'countries' => Helpers::getCountries(),
            'languages' => $languages,
            'zones' => $this->getZones(),
        ]);
    }

    public function getZones()
    {
        return $this->zone->where('status', true)->pluck('name', 'id');
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {

            if ($request->type === UserTypeEnum::COMPANY) {
                $company = $this->company->create([
                    'name' => $request->company_name,
                    'email' => $request->company_email,
                    'code' => $request->company_code,
                    'phone' => $request->company_phone,
                    'description' => $request->company_description,
                ]);

                if ($request->hasFile('company_logo') && $request->file('company_logo')->isValid()) {
                    $company->addMediaFromRequest('company_logo')->toMediaCollection('company_logo');
                }
            }

            $provider = $this->model->create([
                'company_id' => $company->id ?? null,
                'experience_interval' => $request->experience_interval,
                'experience_duration' => $request->experience_duration,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'code' => $request->code,
                'status' => $request->status,
                'type' => $request->type,
                'password' => Hash::make($request->password),
                'description' => $request->description,
            ]);

            $role = $this->role->where('name', RoleEnum::PROVIDER)->first();
            if ($request->role) {
                $role = $this->role->findOrFail($request->role);
            }

            $provider->assignRole($role);
            if (isset($request->known_languages)) {
                $provider->knownLanguages()->attach($request->known_languages);
                $provider->knownLanguages;
            }

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $provider->addMediaFromRequest('image')->toMediaCollection('image');
            }

            if (isset($request->zones)) {
                $provider->zones()->attach($request->zones);
                $provider->zones;
            }

            $bankDetail = $this->bankDetail->create([
                'user_id' => $provider->id,
                'bank_name' => $request->bank_name,
                'holder_name' => $request->holder_name,
                'account_number' => $request->account_number,
                'branch_name' => $request->branch_name,
                'ifsc_code' => $request->ifsc_code,
                'swift_code' => $request->swift_code,
            ]);

            if ($request->is_primary === 'true') {
                $this->model->query()->where('user_id', Helpers::getCurrentUserId())->update([
                    'is_primary' => false,
                ]);
            }

            $address = $this->address->create([
                'user_id' => $provider->id,
                'type' => $request->address_type,
                'postal_code' => $request->postal_code,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'city' => $request->city,
                'code' => $request->alternative_code,
                'address' => $request->address,
                'area' => $request->area,
                'alternative_name' => $request->alternative_name,
                'alternative_phone' => $request->alternative_phone,
                'is_primary' => true,
            ]);

            event(new CreateProviderEvent($provider));
            DB::commit();

            return redirect()->route('backend.provider.index')->with('message', 'Provider Created Successfully.');
        } catch (Exception $e) {

            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $provider = $this->model->findOrFail($id);
            $languages = $this->language->get();
            $services = $this->service->where('user_id', $provider->id)->pluck('title', 'id');
            $zones = $this->zone->pluck('name', 'id');

            return view('backend.provider.edit', [
                'zones' => $zones,
                'provider' => $provider,
                'languages' => $languages,
                'services' => $services,
                'default_zones' => $this->getDefaultZones($provider),
                'countries' => Helpers::getCountries(),
                'default_languages' => $this->getDefaultLanguages($provider),
                'default_services' => $this->getDefaultService($provider),
            ]);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function getDefaultLanguages($provider)
    {
        $languages = [];
        foreach ($provider->knownLanguages as $language) {
            $languages[] = $language->id;
        }
        $languages = array_map('strval', $languages);

        return $languages;
    }

    public function getDefaultService($provider)
    {
        $services = [];
        foreach ($provider->expertise as $service) {
            $services[] = $service->id;
        }
        $services = array_map('strval', $services);

        return $services;
    }

    public function getDefaultZones($provider)
    {
        $zones = [];
        foreach ($provider->zones as $zone) {
            $zones[] = $zone->id;
        }
        $zones = array_map('strval', $zones);

        return $zones;
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $provider = $this->model->findOrFail($id);
            if ($provider->system_reserve) {
                return redirect()->route('backend.provider.index')->with('error', 'This User Cannot be Update. It is System reserved.');
            }
            if ($request['type'] === UserTypeEnum::COMPANY) {
                $provider->company->update([
                    'name' => $request['company_name'],
                    'email' => $request['company_email'],
                    'code' => $request['company_code'],
                    'phone' => $request['company_phone'],
                    'description' => $request['company_description'],
                ]);
                if ($request['company_logo']) {
                    $provider->company->clearMediaCollection('company_logo');
                    $provider->company->addMediaFromRequest('company_logo')->toMediaCollection('company_logo');
                }
            }

            $provider->update([
                'name' => $request['name'],
                'email' => $request['email'],
                'phone' => (string) $request['phone'],
                'code' => $request['code'],
                'status' => $request['status'],
                'type' => $request['type'],
                'description' => $request['description'],
                'experience_interval' => $request['experience_interval'],
                'experience_duration' => $request['experience_duration'],
            ]);

            $role = $this->role->where('name', RoleEnum::PROVIDER)->first();
            $provider->syncRoles($role);

            if ($request['image']) {
                $provider->clearMediaCollection('image');
                $provider->addMediaFromRequest('image')->toMediaCollection('image');
            }

            if (isset($request['known_languages'])) {
                $provider->knownLanguages()->sync($request['known_languages']);
            }

            if (isset($request['expertiseIN'])) {
                $provider->expertise()->sync($request['expertiseIN']);
            }

            if (isset($request['zones'])) {
                $provider->zones()->sync($request['zones']);
                $provider->zones;
            }

            $provider->bankDetail->update([
                'bank_name' => $request['bank_name'],
                'holder_name' => $request['holder_name'],
                'account_number' => $request['account_number'],
                'branch_name' => $request['branch_name'],
                'ifsc_code' => $request['ifsc_code'],
                'swift_code' => $request['swift_code'],
            ]);

            DB::commit();

            return redirect()->route('backend.provider.index')->with('message', 'Provider Updated Successfully.');
        } catch (Exception $e) {

            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {

            $user = $this->model->findOrFail($id);
            if ($user->hasRole(RoleEnum::ADMIN)) {
                return redirect()->route('backend.role.index')->with('error', 'System reserved.');
            }
            if ($user->company) {
                $user->company->delete();
            }
            $user->destroy($id);

            return redirect()->route('backend.provider.index')->with('message', 'Provider Deleted Successfully');
        } catch (Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }

    public function status($id, $status)
    {
        try {

            $user = $this->model->findOrFail($id);
            $user->update([
                'status' => $status,
            ]);

            return json_encode(['resp' => $user]);
        } catch (Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }

    public function updatePassword($request, $id)
    {
        try {
            $this->model->findOrFail($id)->update([
                'password' => Hash::make($request->new_password),
            ]);

            return back()->with('message', 'User Password Update Successfully.');
        } catch (Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }

    protected function getProvidersWithAddress()
    {
        try {
            return $this->model->role('provider')->with([
                'addresses' => function ($query) {
                    $query->where('is_primary', true)->get();
                },
            ])->get();
        } catch (Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }

}

