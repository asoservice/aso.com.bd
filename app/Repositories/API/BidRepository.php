<?php

namespace App\Repositories\API;

use App\Enums\BidStatusEnum;
use App\Enums\RoleEnum;
use App\Enums\ServiceRequestEnum;
use App\Enums\ServiceTypeEnum;
use App\Events\CreateBidEvent;
use App\Events\UpdateBidEvent;
use App\Exceptions\ExceptionHandler;
use App\Helpers\Helpers;
use App\Models\Bid;
use App\Models\Service;
use App\Models\ServiceRequest;
use Exception;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;
use Symfony\Component\HttpFoundation\Response;
use Prettus\Repository\Criteria\RequestCriteria;


class BidRepository extends BaseRepository
{
    protected $serviceRequest;

    protected $service;

    public function model()
    {
        $this->serviceRequest = new ServiceRequest();
        $this->service = new Service();
        return Bid::class;
    }

    public function  boot()
    {
        try {

            $this->pushCriteria(app(RequestCriteria::class));
        } catch (Exception $e) {

            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            if (Helpers::getCurrentRoleName() !== RoleEnum::PROVIDER) {
                throw new Exception('errors.only_providers_can_place_bids',  Response::HTTP_BAD_REQUEST);
            }

            $provider_id = $request->provider_id ?? Helpers::getCurrentUserId();
           
            if (!$this->isExistsBidAtTime($provider_id, $request->service_request_id)) {
                $bid = $this->model->create([
                    'service_request_id' => $request->service_request_id,
                    'amount' => $request->amount,
                    'provider_id' => $provider_id,
                ]);
                event(new CreateBidEvent($bid));
                DB::commit();
                return $bid;
            }
            throw new Exception('You cannot create a new bid until the previous bid has been rejected.', 400);
        } catch (Exception $e) {
            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    public function isExistsBidAtTime($provider_id, $service_request_id)
    {
      return $this->model->whereNull('deleted_at')
          ?->where('provider_id',$provider_id)
          ?->where('service_request_id',$service_request_id)
          ?->where('status',BidStatusEnum::REQUESTED)?->exists();
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {

            $bid = $this->model->findOrFail($id);
            if ($bid->status === BidStatusEnum::REQUESTED) {
                $bid->update(['status' => $request['status']]);
                if($bid->status == BidStatusEnum::REJECTED){
                    $serviceRequest = $this->serviceRequest::findOrFail($bid->service_request_id);
                    $serviceRequest->update([
                        'status' => ServiceRequestEnum::PENDING
                    ]);
                }
                event(new UpdateBidEvent($bid));
                DB::commit();
                $bid = $bid->fresh();
                if ($bid->status == BidStatusEnum::ACCEPTED) {
                    $service = $this->createService($bid->service_request_id, $bid);
                    return $service;
                }
                event(new UpdateBidEvent($bid));
                return $bid;
            }

            throw new Exception("The bid status is already {$bid?->status}, you can't change again.", 403);
        } catch (Exception $e) {

            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    public function createService($service_request_id, $bid)
    {
        DB::beginTransaction();
        try {

            $serviceRequest = $this->serviceRequest::findOrFail($service_request_id);
            $serviceRequestSettings = Helpers::getServiceRequestSettings();
            if ($serviceRequest && $serviceRequest->status != ServiceRequestEnum::CLOSED) {
                $bid = $serviceRequest?->getAcceptedBid();
                $service = $this->service::create([
                    'service_request_id' => $serviceRequest->id,
                    'title' => $serviceRequest->title,
                    'description' => $serviceRequest->description,
                    'duration' => $serviceRequest->duration,
                    'duration_unit' => $serviceRequest->duration_unit,
                    'required_servicemen' => $serviceRequest->required_servicemen,
                    'per_serviceman_commission' => $serviceRequestSettings['per_serviceman_commission'],
                    'tax_id' => $serviceRequestSettings['default_tax_id'],
                    'price' => $bid->amount,
                    'service_rate' => $bid->amount,
                    'user_id' => $bid->provider_id,
                    'created_by_id' => $serviceRequest->user_id,
                    'is_random_related_services' => true,
                    'type' => ServiceTypeEnum::FIXED,
                    'status' => true,
                ]);

                $rand_service_id = $serviceRequest->category_ids[array_rand($serviceRequest->category_ids)];
                $related_service_ids = Helpers::getRelatedServiceId($service, $rand_service_id, $service->id);
                $service->related_services()->attach($related_service_ids);
                
                if (isset($serviceRequest->category_ids)) {
                    $service->categories()->attach($serviceRequest->category_ids);
                    $service->categories;
                }

                $this->model->where('service_request_id', $bid->service_request_id)?->whereNot('id', $bid->id)?->update([
                    'status' => BidStatusEnum::REJECTED
                ]);

                $serviceRequest->update([
                    'status' => ServiceRequestEnum::CLOSED,
                    'provider_id' => $bid->provider_id,
                    'final_price' => $bid->amount,
                    'service_id' => $service->id
                ]);


                DB::commit();
                return $service;
            }

            throw new Exception(__('errors.invalid_service_request'), 400);
        } catch (Exception $e) {

            DB::rollBack();
            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }
}
