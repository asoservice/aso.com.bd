<?php

namespace App\Repositories\API;

use App\Enums\RoleEnum;
use App\Events\CreateProviderEvent;
use App\Exceptions\ExceptionHandler;
use App\Helpers\Helpers;
use App\Models\Address;
use App\Models\Booking;
use App\Models\Service;
use App\Models\TimeSlot;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Prettus\Repository\Eloquent\BaseRepository;

class ProviderRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name' => 'like',
    ];

    protected $role;

    protected $service;

    protected $address;

    protected $timeslot;

    protected $booking;

    public function model()
    {
        $this->address = new Address();
        $this->role = new Role();
        $this->service = new Service();
        $this->timeslot = new TimeSlot();
        $this->booking = new Booking();

        return User::class;
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $user = $this->model->create([
                'name' => $request->name,
                'email' => $request->email,
                'country_code' => $request->country_code,
                'phone' => (string) $request->phone,
                'code' => $request->countryCode,
                'status' => $request->status,
                'password' => Hash::make($request->password),
            ]);

            $role = $this->role->where('name', RoleEnum::PROVIDER)->first();
            $user->assignRole($role);

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $user->addMediaFromRequest('image')->toMediaCollection('image');
            }

            $address = $this->address->create([
                'user_id' => $user->id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'area' => $request->area,
                'postal_code' => $request->postal_code,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'city' => $request->city,
                'address' => $request->address,
                'type' => $request->type,
                'is_primary' => 1,
            ]);

            event(new CreateProviderEvent($user));
            DB::commit();

            return response()->json($user);
        } catch (Exception $e) {
            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            return $this->model->role('provider')->with(['addresses'])->findOrFail($id);
        } catch (Exception $e) {

            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    public function isValidTimeSlot($request)
    {
        $bookings = $this->booking->where('provider_id', $request->provider_id)->get();
        $dateTime = Carbon::parse($request->dateTime);

        foreach ($bookings as $booking) {
            $bookingDateTime = Carbon::parse($booking->dateTime);

            if ($dateTime->eq($bookingDateTime)) {
                return response()->json([
                    'success' => true,
                    'isValidTimeSlot' => false,
                ]);
            }
        }

        return response()->json(['success' => true, 'isValidTimeSlot' => true]);
    }

    public function providerTimeslot($providerId)
    {
        $providerTimeSlot = $this->timeslot->where('provider_id', $providerId)->first();

        return $providerTimeSlot;
    }

    public function storeProviderTimeSlot($request)
    {
        DB::beginTransaction();
        try {
            $provider_id = Helpers::getCurrentUserId();
            $this->timeslot->create([
                'time_unit' => $request->time_unit,
                'gap' => $request->gap,
                'time_slots' => $request->time_slots,
                'provider_id' => $provider_id,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('static.provider.time_slot_created'),
            ]);
        } catch (Exception $e) {

            DB::rollback();

            throw $e;
        }
    }

    public function updateProviderTimeSlot($request)
    {
        DB::beginTransaction();
        try {
            $roleName = Helpers::getCurrentRoleName();
            if ($roleName == RoleEnum::PROVIDER) {
                $provider_id = Helpers::getCurrentUserId();
                $timeSlot = $this->timeslot->where('provider_id', $provider_id)->first();
                if ($timeSlot) {
                    $timeSlot->update([
                        'gap' => $request['gap'],
                        'time_unit' => $request['time_unit'],
                        'time_slots' => $request['time_slots'],
                    ]);
                    DB::commit();

                    return response()->json([
                        'success' => true,
                        'message' => __('static.provider.time_slot_updated'),
                    ]);
                } else {
                    return response()->json([
                        'message' => __('static.provider.create_time_slot'),
                        'success' => false,
                    ]);
                }
            } else {
                return response()->json([
                    'message' => __('static.provider.auth_is_not_provider'),
                    'success' => false,
                ]);
            }
        } catch (Exception $e) {

            DB::rollback();
            throw $e;
        }
    }

    public function updateTimeSlotStatus($status, $timeslotID)
    {
        DB::beginTransaction();
        try {
            $timeSlot = $this->timeslot->findOrFail($timeslotID);
            $provider_id = Helpers::getCurrentUserId();
            $timeSlot->update(['status' => $status]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('static.provider.time_slot_status_updated'),
            ]);
        } catch (Exception $e) {

            DB::rollback();
            throw $e;
        }
    }

    public function getUsersWithHighestRatings($request)
    {
        $searchQuery = $request->search;

        $expertServicer = $this->model
            ->role('provider')
            ->with('services')
            ->when($searchQuery, function ($query) use ($searchQuery) {
                $query->where('name', 'like', '%'.$searchQuery.'%');
            })
            ->get()
            ->filter(function ($provider) {
                return $provider->review_ratings > 0;
            })
            ->sortByDesc(function ($provider) {
                return $provider->review_ratings;
            });

        return response()->json($expertServicer);
    }

    public function getProviderServices($request)
    {
        $providerId = Helpers::getCurrentProviderId();
        $provider = $this->model::findOrFail($providerId);
        if ($request->service_id) {
            $service = $provider->services()->where('id', $request->service_id)->with('serviceAvailabilities', 'tax')->first();
            if ($service) {
                return response()->json([
                    'success' => true,
                    'data' => $service,
                ]);
            } else {
                return response()->json([
                    'message' => __('static.provider.service_not_found'),
                    'success' => false,
                ]);
            }
        } else {
            if ($provider) {
                $services = $provider->services()->with(['addresses', 'user', 'serviceAvailabilities', 'tax:name']);
                if ($request->popular_service) {
                    $services = Helpers::getTopSellingServicec($provider->services());
                }

                if ($request->category_id) {
                    $categoryId = $request->category_id;
                    $services->whereHas('categories', function ($query) use ($categoryId) {
                        $query->where('category_id', $categoryId);
                    });
                }

                if ($request->search) {
                    $services->where('title', 'like', '%'.$request->search.'%');
                }

                return $services->latest('created_at')->paginate($request->paginate ?? $services->count());
            } else {
                return response()->json([
                    'message' => __('static.provider.invalid_provider'),
                    'success' => false,
                ]);
            }
        }
    }

    public function updateProviderZones($request)
    {
        $user_id = Helpers::getCurrentUserId();
        $provider = $this->model->findOrFail($user_id);
        if ($provider) {
            $roleName = Helpers::getCurrentRoleName();
            if ($roleName == RoleEnum::PROVIDER) {
                if (isset($request->zoneIds)) {
                    $provider->zones()->sync([]);
                    $provider->zones()->sync($request->zoneIds);

                    return response()->json([
                        'message' => __('static.provider.zone_id_updated'),
                        'success' => false,
                    ]);
                }

                return response()->json([
                    'message' => __('static.provider.zone_id_must_be_required'),
                    'success' => false,
                ]);
            }

            return response()->json([
                'message' => __('static.provider.must_be_provider'),
                'success' => false,
            ]);
        }

        return response()->json([
            'message' => __('static.provider.not_found'),
            'success' => false,
        ]);
    }

    public function updateCompanyDetails($request)
    {
        $provider = Helpers::getCurrentUser();
        if ($provider) {
            $roleName = Helpers::getCurrentRoleName();
            if ($roleName == RoleEnum::PROVIDER) {

            }

            return response()->json([
                'message' => __('static.provider.must_be_provider'),
                'success' => false,
            ]);
        }

        return response()->json([
            'message' => __('static.provider.not_found'),
            'success' => false,
        ]);
    }
}
