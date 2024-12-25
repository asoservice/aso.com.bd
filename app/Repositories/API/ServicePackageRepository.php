<?php

namespace App\Repositories\API;

use App\Enums\RoleEnum;
use App\Exceptions\ExceptionHandler;
use App\Helpers\Helpers;
use App\Models\ServicePackage;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

class ServicePackageRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'title' => 'like',
    ];

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
        return ServicePackage::class;
    }

    public function isProviderCanCreate()
    {
        if (Helpers::isUserLogin()) {
            $isAllowed = true;
            $roleName = Helpers::getCurrentRoleName();
            if ($roleName == RoleEnum::PROVIDER) {
                $isAllowed = false;
                $provider = Auth::user();
                $maxItems = $this->model->where('provider_id', Auth::user()?->id)?->whereNUll('deleted_at')?->count() ?? 0;
                if (Helpers::isModuleEnable('Subscription')) {
                    if (function_exists('isPlanAllowed')) {
                        $isAllowed = isPlanAllowed('allowed_max_service_packages', $maxItems, $provider?->id);
                    }
                }

                if (! $isAllowed) {
                    $settings = Helpers::getSettings();
                    $max_service_packages = $settings['default_creation_limits']['allowed_max_service_packages'];
                    if ($max_service_packages > $maxItems) {
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
                $service_package = $this->model->create([
                    'title' => $request->title,
                    'hexa_code' => $request->hexa_code,
                    'price' => $request->price,
                    'discount' => $request->discount,
                    'description' => $request->description,
                    'disclaimer' => $request->disclaimer,
                    'is_featured' => $request->is_featured,
                    'provider_id' => $request->provider_id,
                    'status' => $request->status,
                    'started_at' => Carbon::createFromFormat('j-M-Y', $request->started_at)->format('Y-m-d'),
                    'ended_at' => Carbon::createFromFormat('j-M-Y', $request->ended_at)->format('Y-m-d'),
                ]);

                if (isset($request->service_id)) {
                    $service_package->services()->attach($request->service_id);
                    $service_package->services;
                }

                if ($request->hasFile('image') && $request->file('image')->isValid()) {
                    $service_package->addMediaFromRequest('image')->toMediaCollection('service_package_image');
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => __('static.service_package.store'),
                    'service-package' => $service_package,
                ]);
            }

            throw new Exception(__('static.not_allow_for_creation'), 400);
        } catch (Exception $e) {

            DB::rollback();
            throw $e;
        }
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $service_package = $this->model->findOrFail($id);
            if (! $service_package) {
                return response()->json([
                    'success' => false,
                    'message' => __('static.service_package.service_package_not_found'),
                ]);
            }
            $startedAt = Carbon::createFromFormat('j-M-Y', $request->started_at)->format('Y-m-d');
            $endedAt = Carbon::createFromFormat('j-M-Y', $request->ended_at)->format('Y-m-d');

            $request->merge([
                'started_at' => $startedAt,
                'ended_at' => $endedAt,
            ]);
            $service_package->update($request->all());
            if ($request->service_id) {
                $service_package->services()->sync($request->service_id);
                $service_package->services;
            }
            if ($request->hasFile('image')) {
                $service_package->clearMediaCollection('service_package_image');
                $service_package->addMedia($request->image)->toMediaCollection('service_package_image');
            }
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('static.service_package.updated'),
            ]);
        } catch (Exception $e) {
            DB::rollback();
            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $service_package = $this->model->findOrFail($id);
            if (! $service_package) {
                return response()->json([
                    'success' => false,
                    'message' => __('static.service_package.service_package_not_found'),
                ]);
            }
            $service_package->services()->detach();
            $service_package->clearMediaCollection('service_package_image');
            $service_package->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('static.service_package.destroy'),
            ]);
        } catch (Exception $e) {
            DB::rollback();
            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }
}
