<?php

namespace App\Http\Controllers\Auth;

use App\Enums\RoleEnum;
use App\Enums\UserTypeEnum;
use App\Events\CreateProviderEvent;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BecomeProviderRequest;
use App\Models\Address;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class BecomeProviderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function index()
    {
        return view('auth.register');
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @return \App\Models\User
     */
    protected function store(BecomeProviderRequest $request)
    {
        DB::beginTransaction();
        try {

            $data = $request?->all();
            $company = null;
            // Company creation
            if ($data['type'] === UserTypeEnum::COMPANY) {
                $company = Company::create([
                    'name' => $data['company_name'],
                    'email' => $data['company_email'],
                    'code' => $data['company_code'],
                    'phone' => $data['company_phone'],
                    'description' => $data['company_description'],
                ]);

                if (isset($data['company_logo']) && $data['company_logo']?->isValid()) {
                    $company->addMedia($data['company_logo'])->toMediaCollection('company_logo');
                }
            }

            // Provider creation
            $provider = User::create([
                'company_id' => $company?->id ?? null,
                'experience_interval' => $data['experience_interval'],
                'experience_duration' => $data['experience_duration'],
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'code' => $data['code'],
                'type' => $data['type'],
                'password' => Hash::make($data['password']),
              
            ]);

            // Assign role
            $role = Role::where('name', RoleEnum::PROVIDER)->first();
            $provider->assignRole($role);

            // Handle known languages
            if (isset($data['known_languages'])) {
                $provider->knownLanguages()->attach($data['known_languages']);
            }

            // Handle company logo image
            if (isset($data['image']) && $data['image']?->isValid()) {
                $company->addMedia($data['image'])->toMediaCollection('image');
            }

            // Handle zones
            if (isset($data['zones'])) {
                $provider->zones()->attach($data['zones']);
            }

            // Create address
            Address::create([
                'user_id' => $provider->id,
                'type' => $data['address_type'] ?? null,
                'postal_code' => $data['postal_code'] ?? null,
                'country_id' => $data['country_id'] ?? null,
                'state_id' => $data['state_id'] ?? null,
                'city' => $data['city'] ?? null,
                'code' => $data['alternative_code'] ?? null,
                'address' => $data['address'] ?? null,
                'area' => $data['area'] ?? null,
                'alternative_name' => $data['alternative_name'] ?? null,
                'alternative_phone' => $data['alternative_phone'] ?? null,
                'is_primary' => true,
            ]);

            DB::commit();

            // Log the user in
            Auth::login($provider);
            // event(new CreateProviderEvent($provider));
            return redirect()->route('backend.dashboard')->with('message', 'Provider Registered Successfully.');

        } catch (Exception $e) {
            DB::rollback();

            dd($e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }
}
