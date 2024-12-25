<?php

namespace App\Repositories\Backend;

use App\Enums\RoleEnum;
use App\Helpers\Helpers;
use App\Models\Service;
use App\Models\ServicePackage;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Prettus\Repository\Eloquent\BaseRepository;

class ServicePackageRepository extends BaseRepository
{
    protected $service;

    protected $user;

    public function model()
    {
        $this->service = new Service();
        $this->user = new User();

        return ServicePackage::class;
    }

    public function index()
    {
        return view('backend.service-package.index');
    }

    protected function getProviders()
    {
        return $this->user->role('provider')->get();
    }

    public function create($attributes = [])
    {
        return view('backend.service-package.create', [
            'providers' => $this->getProviders(),
        ]);
    }

    public function getProviderServices($request)
    {
        $data = $this->service->where('user_id', $request->provider_id)
    ->where('id', '!=', $request->service_id)
    ->with(['media' => function($query) {
        $query->where('collection_name', 'thumbnail'); 
    }])
    ->get();
        return response()->json($data);
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

                if (!$isAllowed) {
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
                [$start_date, $end_date] = explode(' to ', $request->start_end_date);
                $start_date = DateTime::createFromFormat('d-m-Y', $start_date)->format('Y-m-d');
                $end_date = DateTime::createFromFormat('d-m-Y', $end_date)->format('Y-m-d');
                $service_package = $this->model->create([
                    'title' => $request->title,
                    'hexa_code' => $request->hexa_code,
                    'bg_color' => $request?->bg_color,
                    'price' => $request->price,
                    'discount' => $request->discount,
                    'description' => $request->description,
                    'is_featured' => $request->is_featured,
                    'provider_id' => $request->provider_id,
                    'started_at' => $start_date,
                    'ended_at' => $end_date,
                    'status' => $request->status,
                ]);

                if (isset($request->service_id)) {
                    $service_package->services()->attach($request->service_id);
                    $service_package->services;
                }

                if ($request->hasFile('image')) {
                    $service_package->addMediaFromRequest('image')->toMediaCollection('image');
                }

                DB::commit();

                return redirect()->route('backend.service-package.index')->with('message', 'Service-Package Created Successfully.');

            }

            throw new Exception(__('static.not_allow_for_creation'), 400);
        } catch (Exception $e) {

            DB::rollback();
            throw $e;
        }
    }

    public function edit($id)
    {
        $service_package = $this->model->findOrFail($id);
        return view('backend.service-package.edit', [
            'services' => $this->getServices($service_package),
            'service_package' => $service_package,
            'default_services' => $this->getDefaultServices($service_package),
            'providers' => $this->getProviders(),
        ]);
    }

    public function getDefaultServices($service_package)
    {
        $services = [];
        foreach ($service_package->services as $service) {
            $services[] = $service->id;
        }
        $services = array_map('strval', $services);

        return $services;
    }

    public function update($request, $id)
    {
        DB::beginTransaction();

        try {
            if (! is_null($request->start_end_date)) {
                [$start_date, $end_date] = explode(' to ', $request->start_end_date);
                $request->merge(['stated_at' => $start_date, 'ended_at' => $end_date]);
            }

            $service_package = $this->model->findOrFail($id);
            $service_package->update($request->all());

            if (isset($request->service_id)) {
                $service_package->services()->sync($request->service_id);
            }

            if ($request->file('image')) {
                $service_package->clearMediaCollection('image');
                $service_package->addMediaFromRequest('image')->toMediaCollection('image');
            }

            DB::commit();

            return redirect()->route('backend.service-package.index')->with('message', 'Service-Package Updated Successfully.');
        } catch (Exception $e) {

            DB::rollback();
            throw $e;
        }
    }

    public function status($id, $status)
    {
        try {
            $service_package = $this->model->findOrFail($id);
            $service_package->update([
                'status' => $status,
            ]);

            return json_encode(['resp' => $service_package]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function destroy($id)
    {
        try {

            $service_package = $this->model->findOrFail($id);
            $service_package->services()->detach();
            $service_package->destroy($id);

            return redirect()->route('backend.service-package.index')->with('message', 'Service-Package Deleted Successfully');
        } catch (Exception $e) {

            throw $e;
        }
    }

    private function getServices($service_package)
    {
        if (FacadesRequest::is('backend/service-package/create')) {
            return $this->service->get();
        } else {
            return $this->service->get()->except($service_package->id);
        }
    }
}
