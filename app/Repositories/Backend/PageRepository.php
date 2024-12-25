<?php

namespace App\Repositories\Backend;

use App\Models\Page;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class PageRepository extends BaseRepository
{
    public function model()
    {
        return Page::class;
    }

    public function index()
    {
        return view('backend.page.index');
    }

    public function create($attribute = [])
    {
        return view('backend.page.create');
    }

    public function show($id) {}

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $page = $this->model->create(
                [
                    'title' => $request->title,
                    'content' => $request->content,
                    'meta_title' => $request->metatitle,
                    'meta_description' => $request->metadescription,
                    'created_by_id' => Auth::user()->id,
                    'status' => $request->status,
                ]
            );

            if ($request->hasFile('meta_image') && $request->file('meta_image')->isValid()) {
                $page->addMediaFromRequest('meta_image')->toMediaCollection('meta_image');
            }

            if ($request->hasFile('app_icon') && $request->file('app_icon')->isValid()) {
                $page->addMediaFromRequest('app_icon')->toMediaCollection('app_icon');
            }

            DB::commit();

            return redirect()->route('backend.page.index')->with('message', 'Page Created Successfully.');
        } catch (Exception $e) {

            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $page = $this->model->find($id);

        return view('backend.page.edit', [
            'page' => $page,
        ]);
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {

            $page = $this->model->findOrFail($id);
            $page->update([
                'title' => $request->title,
                'content' => $request->content,
                'meta_title' => $request->metatitle,
                'meta_description' => $request->metadescription,
                'created_by_id' => Auth::user()->id,
                'status' => $request->status,
            ]);

            if ($request->hasFile('meta_image') && $request->file('meta_image')->isValid()) {
                $page->clearMediaCollection('meta_image');
                $page->addMediaFromRequest('meta_image')->toMediaCollection('meta_image');
            }

            if ($request->hasFile('app_icon') && $request->file('app_icon')->isValid()) {
                $page->clearMediaCollection('app_icon');
                $page->addMediaFromRequest('app_icon')->toMediaCollection('app_icon');
            }

            DB::commit();

            return redirect()->route('backend.page.index')->with('success', 'Page Updated Successfully.');
        } catch (Exception $e) {

            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $page = $this->model->findOrFail($id);
            $page->destroy($id);

            DB::commit();

            return redirect()->back()->with(['message' => 'Page deleted successfully']);
        } catch (Exception $e) {

            DB::rollback();

            return back()->with(['error' => $e->getMessage()]);
        }
    }

    public function status($id, $status)
    {
        try {

            $page = $this->model->findOrFail($id);
            $page->update(['status' => $status]);

            return json_encode(['resp' => $page]);
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
