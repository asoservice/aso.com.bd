<?php

namespace App\Repositories\Backend;

use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class CategoryRepository extends BaseRepository
{
    public $zones;

    public function model()
    {
        return Category::class;
    }

    public function show($id)
    {
        try {

            return $this->model->with('permissions')->findOrFail($id);
        } catch (Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }

    public function getHierarchy()
    {
        return collect($this->model->getHierarchy())->pluck('title', 'id');
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $category = $this->model->create(
                [
                    'title' => $request->title,
                    'description' => $request->description,
                    'parent_id' => $request->parent_id,
                    'commission' => $request->commission,
                    'status' => $request->status,
                    'category_type' => $request->category_type,
                    'is_featured' => $request->is_featured,
                    'created_by' => Auth::user()->id,
                ]
            );
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $category->addMediaFromRequest('image')->toMediaCollection('image');
            }

            if (isset($request->zones)) {
                $category->zones()->attach($request->zones);
                $category->zones;
            }

            DB::commit();

            return redirect()->route('backend.category.index')->with('message', 'Category Created Successfully.');
        } catch (Exception $e) {

            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $category = $this->model->findOrFail($id);
            $category->update($request->all());

            if ($request->file('image') && $request->file('image')->isValid()) {
                $category->clearMediaCollection('image');
                $category->addMediaFromRequest('image')->toMediaCollection('image');
            }

            if (isset($request->zones)) {
                $category->zones()->sync($request->zones);
                $category->zones;
            }

            DB::commit();

            return redirect()->route('backend.category.edit', $category->id)->with('success', 'Category Updated Successfully.');
        } catch (Exception $e) {

            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $category = $this->model->findOrFail($id);
            $category->destroy($id);

            DB::commit();

            return redirect()->route('backend.category.index');
        } catch (Exception $e) {

            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }

    public function changeIsFeatured($isFeatured, $subjectId)
    {
        DB::beginTransaction();
        try {
            $category = $this->model->findOrFail($subjectId);
            $category->is_featured = $isFeatured;
            $category->save();

            DB::commit();

            return redirect()->route('backend.category.index')->with('message', 'Is Featured Updated Successfully');
        } catch (Exception $e) {

            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }

    public function changeStatus($statusVal, $subjectId)
    {
        DB::beginTransaction();
        try {
            $category = $this->model->findOrFail($subjectId);
            $category->status = $statusVal;
            $category->save();

            DB::commit();

            return redirect()->route('backend.category.index')->with('message', 'Is Featured Updated Successfully');
        } catch (Exception $e) {

            DB::rollback();

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
