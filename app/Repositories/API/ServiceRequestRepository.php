<?php

namespace App\Repositories\API;

use App\Enums\ServiceRequestEnum;
use App\Events\CreateServiceRequestEvent;
use Exception;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\DB;
use App\Exceptions\ExceptionHandler;
use App\Helpers\Helpers;
use Prettus\Repository\Eloquent\BaseRepository;
use Symfony\Component\HttpFoundation\Response;

class ServiceRequestRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'title' => 'like',
        'initial_price' => 'like'
    ];

    function model()
    {
        return ServiceRequest::class;
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $serviceRequest = $this->model->create([
                'title' => $request->title,
                'description' => $request->description,
                'duration' => $request->duration,
                'duration_unit' => $request->duration_unit,
                'required_servicemen' => $request->required_servicemen,
                'initial_price' => $request->initial_price,
                'user_id' => Helpers::getCurrentUserId(),
                'booking_date' => $request->booking_date,
                'category_ids' => $request->category_ids
            ]);

            if ($request->image) {
                $images = $request->file('image');
                foreach ($images as $image) {
                    $serviceRequest->addMedia($image)->toMediaCollection('image');
                }
                $serviceRequest->media;
            }

            event(new CreateServiceRequestEvent($serviceRequest));            

            DB::commit();
            return $serviceRequest;

        } catch (Exception $e) {

            DB::rollback();
            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {

            return $this->model->with(['media', 'user:id,name,email', 'bids'])->findOrFail($id);

        } catch (Exception $e) {

            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {

            $serviceRequest = $this->model->findOrFail($id);
            $serviceRequest?->destroy($id);
            
            return response()->json([
                'success' => true,
                'message' => __('static.service_request.destroy'),
            ]);

        } catch (Exception $e) {

            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }
}
