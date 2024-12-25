<?php

namespace App\Http\Controllers\API;

use App\Enums\RoleEnum;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\CreateServiceRequest;
use App\Http\Requests\API\StoreServiceAddressRequest;
use App\Http\Requests\API\UpdateServiceRequest;
use App\Models\Service;
use App\Models\ServicePackage;
use App\Models\User;
use App\Repositories\API\ServiceRepository;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public $repository;

    public $servicePackage;

    public $model;

    public function __construct(ServiceRepository $repository, Service $service, ServicePackage $servicePackage)
    {
        $this->authorizeResource(Service::class, 'service', [
            'except' => ['index', 'show'],
        ]);
        $this->model = $service;
        $this->repository = $repository;
        $this->servicePackage = $servicePackage;
    }

    public function index(Request $request)
    {
        $filterService = [];
        $services = Service::where(['status' => true])->whereNull('parent_id')->with(['addresses', 'user', 'additionalServices:id,title,price,status,user_id,parent_id'])->latest('created_at');
        if ($request->zone_ids) {
            $zone_ids = explode(',', $request->zone_ids);
            $services = Helpers::getServicesByZoneIds($zone_ids);
        }

        if ($request->popular_service) {
            $services = Helpers::getTopSellingServicec($this->repository);
        }

        if ($request->search) {
            $services->where('title', 'like', '%'.$request->search.'%');
        }

        if (! $request->search && $request->status) {
            $services->where('status', true);
        }

        if ($request->categoryIds) {
            $categoryIds = explode(',', $request->categoryIds);
            $services = $services->whereHas('categories', function ($categories) use ($categoryIds) {
                $categories->whereIn('category_id', $categoryIds);
            });
        }

        if ($request->rating) {
            $rating = explode(',', $request->rating);
            $services = $this->getServiceByRating($rating, $services);
        }

        if ($request->providerIds) {
            $providerIds = explode(',', $request->providerIds);
            $services = $services->whereHas('user', function ($user) use ($providerIds) {
                $user->whereIn('id', $providerIds);
            });
        }

        if ($request->serviceId) {
            $services = $this->model->where('id', $request->serviceId)->with(['user', 'related_services', 'additionalServices:id,title,price,status,user_id,parent_id']);
        }

        if (isset($request->max) && isset($request->min)) {
            $services->whereBetween('price', [$request->min, $request->max]);
        }

        if ($request->latitude && $request->longitude || $request->distanceRange) {
            $filteredServices = $services->get();
            $servicesWithDistance = collect();

            foreach ($filteredServices as $service) {
                $serviceAddress = $service->addresses->first();
                if ($serviceAddress) {
                    $serviceLatitude = $serviceAddress->latitude;
                    $serviceLongitude = $serviceAddress->longitude;
                    $distance = $this->model->calculateDistance($serviceLatitude, $serviceLongitude, $request->latitude, $request->longitude);
                    $service->distance = $distance;
                    $servicesWithDistance->push($service);
                    if ($request->distanceRange) {
                        if ($distance <= $request->distanceRange) {
                            $service->distance = $distance;
                            $servicesWithDistance->push($service);
                            $filterService[] = $servicesWithDistance;
                        } else {
                            return response()->json(['success' => true, 'message' => 'Data Not Found']);
                        }
                    }
                }
            }

            $services = $servicesWithDistance;
        }

        return response()->json(['success' => true, 'data' => $services->get()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateServiceRequest $request)
    {
        return $this->repository->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        return $this->repository->edit($service->id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        return $this->repository->update($request->all(), $service->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        return $this->repository->destroy($service?->id);
    }

    public function isFeatured(Request $request)
    {
        $services = $this->model->where(['is_featured' => true, 'status' => true]);
        if ($request->zone_ids) {
            $zone_ids = explode(',', $request->zone_ids);
            $services = $services->whereHas('categories', function (Builder $categories) use ($zone_ids) {
                $categories->whereHas('zones', function (Builder $zones) use ($zone_ids) {
                    $zones->WhereIn('zones.id', $zone_ids);
                });
            });
        }

        if ($request->search) {
            $services->where('title', 'like', '%'.$request->search.'%');
        }

        $paginate = $request->input('paginate', $services->count());

        return $services->with('user')->latest('created_at')->paginate($paginate);
    }

    public function servicePackages(Request $request)
    {
        try {

            $servicePackage = $this->servicePackage->where('status', true);
            if (Helpers::isUserLogin()) {
                $roleName = Helpers::getCurrentRoleName();
                if ($roleName == RoleEnum::PROVIDER) {
                    $servicePackage = $servicePackage->where('provider_id', Helpers::getCurrentProviderId());
                }
            }

            if ($request->id) {
                $servicePackage->where('id', $request->id);
            }

            if ($request->zone_ids) {
                $zone_ids = explode(',', $request->zone_ids);
                $servicePackage = $servicePackage->whereHas('services', function (Builder $services) use ($zone_ids) {
                    $services->whereHas('categories', function (Builder $categories) use ($zone_ids) {
                        $categories->whereHas('zones', function (Builder $zones) use ($zone_ids) {
                            $zones->WhereIn('zones.id', $zone_ids);
                        });
                    });
                });
            }

            $paginate = $request->input('paginate', $servicePackage->count());

            return $servicePackage->latest('created_at')->paginate($paginate);

        } catch (Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function storeServiceAddresses(StoreServiceAddressRequest $request, $id)
    {
        return $this->repository->storeServiceAddresses($request, $id);
    }

    public function deleteServiceAddresses($id, $address_id)
    {
        return $this->repository->deleteServiceAddresses($id, $address_id);
    }

    public function serviceFAQS(Request $request)
    {
        return $this->repository->serviceFAQS($request);
    }

    public function getServiceByRating($ratings, $services)
    {
        return $services->where(function ($query) use ($ratings) {
            foreach ($ratings as $rating) {
                $query->orWhere(function ($query) use ($rating) {
                    $query->whereHas('reviews', function ($query) use ($rating) {
                        $query->select('service_id')
                            ->groupBy('service_id')
                            ->havingRaw('AVG(rating) >= ?', [$rating])
                            ->havingRaw('AVG(rating) < ?', [$rating + 1]);
                    });
                });
            }
        });
    }
}
