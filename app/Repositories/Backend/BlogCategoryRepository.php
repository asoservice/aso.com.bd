<?php

namespace App\Repositories\Backend;

use App\Enums\CategoryType;
use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class BlogCategoryRepository extends BaseRepository
{
    public function model()
    {
        return Category::class;
    }

    public function index()
    {
        $categories = $this->model
            ->withOutParent()
            ->where('category_type', CategoryType::BLOG);

        if (!empty(request()->search)) {
            $categories->where('title','LIKE','%'.request()->search.'%');
        }

        $allParent = $this->model->whereNull('parent_id')
            ->where('category_type', CategoryType::BLOG)
            ->get()->pluck('title', 'id');

        return view('backend.blog-category.index', [
            'categories' => $categories->get(),
            'allparent' => $allParent,
        ]);
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
                    'status' => $request->status,
                    'category_type' => $request->category_type,
                    'created_by' => Auth::user()->id,
                ]
            );
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $category->addMediaFromRequest('image')->toMediaCollection('image');
            }

            DB::commit();

            return redirect()->route('backend.blog-category.index')->with('message', 'Category Created Successfully.');
        } catch (Exception $e) {

            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($category, $id)
    {
        $cat = $this->model->find($id);

        return view('backend.blog-category.edit', [
            'cat' => $cat,
            'categories' => $this->model->withOutParent()
                ->where('category_type', CategoryType::BLOG)->get(),
            'allparent' => $this->model->whereNull('parent_id')
                ->where('category_type', CategoryType::BLOG)
                ->get()->pluck('title', 'id'),
        ]);
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $blogCategory = $this->model->findOrFail($id);

            if(is_object($request)){
                if ($request->file('image') && $request->file('image')->isValid()) {
                    $blogCategory->clearMediaCollection('image');
                    $blogCategory->addMediaFromRequest('image')->toMediaCollection('image');
                }

                $blogCategory = $this->model->update([
                    'title' => $request->title,
                    'description' => $request->description,
                    'parent_id' => $request->parent_id,
                    'status' => $request->status,
                    'category_type' => $request->category_type,
                    'created_by' => Auth::user()->id,
                ]);
            }

            DB::commit();

            return redirect()->route('backend.blog-category.index')->with('success', 'Blog Category Updated Successfully.');
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

            return redirect()->route('backend.blog-category.index');
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
