<?php

namespace App\Repositories\Backend;

use App\Models\TimeSlot;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class ProviderTimeSlotRepository extends BaseRepository
{
    protected $provider;

    public function model()
    {
        $this->provider = new User();

        return TimeSlot::class;
    }

    public function index()
    {
        return view('backend.provider-time-slot.index');
    }

    public function create($attribute = [])
    {
        $providers = $this->provider->role('provider')->get();

        return view('backend.provider-time-slot.create', [
            'providers' => $providers,
        ]);
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $timeSlots = [];
            foreach ($request->time_slots as $slot) {
                $timeSlots[] = [
                    'day' => $slot['day'],
                    'status' => $slot['status'],
                    'start_time' => date('g:i A', strtotime($slot['start_time'])),
                    'end_time' => date('g:i A', strtotime($slot['end_time'])),
                ];
            }
            $this->model::create([
                'provider_id' => $request->provider_id,
                'gap' => $request->gap,
                'time_unit' => $request->time_unit,
                'time_slots' => $timeSlots,
            ]);
            DB::commit();

            return redirect()->route('backend.provider-time-slot.index')->with('message', 'Time Slot Created Successfully.');
        } catch (Exception $e) {

            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $timeSlot = $this->model->findOrfail($id);
        $providers = $this->provider->role('provider')->get();

        return view('backend.provider-time-slot.edit', [
            'timeSlot' => $timeSlot,
            'providers' => $providers,
        ]);
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $timeSlot = $this->model->findOrFail($id);

            $timeSlots = [];
            foreach ($request->time_slots as $slot) {
                $timeSlots[] = [
                    'day' => $slot['day'],
                    'status' => $slot['status'],
                    'start_time' => date('g:i A', strtotime($slot['start_time'])),
                    'end_time' => date('g:i A', strtotime($slot['end_time'])),
                ];
            }
            $timeSlot->update([
                'provider_id' => $request->provider_id,
                'gap' => $request->gap,
                'time_unit' => $request->time_unit,
                'time_slots' => $timeSlots,
            ]);

            DB::commit();

            return redirect()->route('backend.provider-time-slot.index')->with('message', 'Time Slot Updated Successfully');
        } catch (Exception $e) {

            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $timeSlot = $this->model->findOrFail($id);
            $timeSlot->destroy($id);

            DB::commit();

            return redirect()->route('backend.provider-time-slot.index')->with('message', __('static.provider_time_slot.time_slots_deleted_successfully'));
        } catch (Exception $e) {

            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function status($id, $status)
    {
        try {

            $timeSlot = $this->model->findOrFail($id);
            $timeSlot->update(['status' => $status]);

            return json_encode(['resp' => $timeSlot]);
        } catch (Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }

    public function deleteRows($request)
    {
        try {
            foreach ($request->id as $row => $key) {
                $providerTimeSlot = $this->model->findOrFail($request->id[$row]);
                $providerTimeSlot->delete();
            }
            return  redirect()->route('backend.provider-time-slot.index')->with('message', __('static.provider_time_slot.time_slots_deleted_successfully'));
        } catch (Exception $e) {
            
            return back()->with('error', $e->getMessage());
        }
    }
}
