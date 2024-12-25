<?php

namespace App\Repositories\Backend;

use App\Enums\RoleEnum;
use App\Helpers\Helpers;
use App\Models\Service;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Prettus\Repository\Eloquent\BaseRepository;

class AdditionalServiceRepository extends BaseRepository
{
    public function model()
    {
        return Service::class;
    }

    public function index()
    {
        return view('backend.additional-service.index');
    }

    public function create($attributes = [])
    {
        return view('backend.additional-service.create', [
            'services' => $this->getServices('service'),
        ]);
    }


    public function store($request)
    {

        DB::beginTransaction();
        try {
            $additionalService = $this->model->create([
                'title' => $request->title,
                'price' => $request->price,
                'parent_id' => $request->parent_id,
                'user_id' => auth()->user()->hasRole(RoleEnum::PROVIDER) ? auth()->id()  : Service::find($request['parent_id'])->user_id,
            ]);

            if ($request->hasFile('thumbnail') && $request->file('thumbnail')->isValid()) {
                $additionalService->addMedia($request->file('thumbnail'))->toMediaCollection('thumbnail');
            }

            DB::commit();
            return  to_route('backend.additional-service.index')->with('message', __('static.additional_service.created'));

        } catch (Exception $e) {

            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }


    public function edit($id)
    {
        $additionalService = $this->model->findOrFail($id);
        return view('backend.additional-service.edit', [
            'additionalService' => $additionalService,
            'services' => $this->getServices($additionalService),
        ]);
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $additionalService = $this->model->findOrFail($id);
            $additionalService->update([
                'title' => $request['title'],
                'price' => $request['price'],
                'status' => $request['status'],
                'parent_id' => $request['parent_id'],
                'user_id' => auth()->user()->hasRole(RoleEnum::PROVIDER) ? auth()->id()  : Service::find($request['parent_id'])->user_id
            ]);
            if ($request['thumbnail']) {
                $additionalService->clearMediaCollection('thumbnail');
                $additionalService->addMedia($request['thumbnail'])->toMediaCollection('thumbnail');
            }
            DB::commit();
            return redirect()->route('backend.additional-service.index')->with('message', __('static.additional_service.updated'));

        } catch (Exception $e) {

            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function status($id, $status)
    {
        try {
            $additionalService = $this->model->findOrFail($id);
            $additionalService->update(['status' => $status]);

            return json_encode(['resp' => $additionalService]);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $additionalService = $this->model->findOrFail($id);
            $additionalService->destroy($id);

            return redirect()->route('backend.additional-service.index')->with('message', __('static.additional_service.deleted'));
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    private function getServices($additionalService)
    {
        $services = $this->model->whereNull('parent_id');
        if(Helpers::getCurrentRoleName() == RoleEnum::PROVIDER){
            $services->where('user_id', auth()->user()->id);
        }
        $serviceList = $services->get();
        if ($additionalService && Request::is('backend/additional-service/*/edit')) {
            return $serviceList->except($additionalService->id);
        }
        return $serviceList;
    }
}
