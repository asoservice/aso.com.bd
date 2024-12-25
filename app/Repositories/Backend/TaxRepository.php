<?php

namespace App\Repositories\Backend;

use App\Models\Tax;
use Exception;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class TaxRepository extends BaseRepository
{
    public function model()
    {
        return Tax::class;
    }

    public function index()
    {
        return view('backend.tax.index');
    }

    public function create($attribute = [])
    {
        return view('backend.tax.create');
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $tax = $this->model->create(
                [
                    'name' => $request->name,
                    'rate' => $request->rate,
                    'status' => $request->status,
                ]
            );

            DB::commit();

            return redirect()->route('backend.tax.index')->with('message', 'Tax Created Successfully.');
        } catch (Exception $e) {

            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $tax = $this->model->findOrFail($id);
        return view('backend.tax.edit', ['tax' => $tax]);
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $tax = $this->model->findOrFail($id);
            $tax->update($request->all());

            DB::commit();

            return redirect()->route('backend.tax.index')->with('success', 'Tax Updated Successfully.');
        } catch (Exception $e) {

            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $tax = $this->model->findOrFail($id);
            $tax->destroy($id);

            DB::commit();

            return redirect()->back()->with(['message' => 'Tax deleted successfully']);
        } catch (Exception $e) {

            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function status($id, $status)
    {
        try {

            $tax = $this->model->findOrFail($id);
            $tax->update(['status' => $status]);

            return json_encode(['resp' => $tax]);
        } catch (Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }

    public function deleteAll($ids)
    {
        DB::beginTransaction();
        try {

            $this->model->whereNot('system_reserve', true)->whereIn('id', $ids)->delete();

            return back()->with('message', 'Roles Deleted Successfully');
        } catch (Exception $e) {

            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }
}
