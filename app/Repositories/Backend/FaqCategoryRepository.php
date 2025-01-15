<?php

namespace App\Repositories\Backend;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\FaqCategory;
use App\Helpers\Helpers;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * Class FaqCategoryRepository.
 *
 * @package namespace App\Repositories\Backend;
 */
class FaqCategoryRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public string $baseMediaPath = 'storage/media/faq-category/';
    public function model()
    {
        return FaqCategory::class;
    }

    public function index()
    {
        $categories = $this->model->latest('id');

        if (!empty(request()->search)) {
            $categories->where('title', 'LIKE', '%' . request()->search . '%');
        }

        return view('backend.faq-category.index', [
            'categories' => $categories->get(),
            'allparent' => []
        ]);
    }

    public function store($request)
    {
        DB::beginTransaction();

        $request->validate([
            'name' => 'required|max:99',
            'icon' => 'nullable|file|image',
            'sort_order' => 'nullable|numeric',
            'status' => 'required',
        ]);

        $status = $request->status == 1 ? 'active' : 'inactive';
        $slug = Helpers::slug($this->model, $request->name);

        try {
            $category = $this->model->create(
                [
                    'name' => $request->name,
                    'description' => $request->description,
                    'sort_order' => $request->sort_order ?? 0,
                    'status' => $status,
                    'slug' => $slug,
                    'icon' => $request->hasFile('icon') ? Helpers::storeFile($request->file('icon'), $this->baseMediaPath) : null
                ]
            );

            DB::commit();
            return redirect()->route('backend.faq-category.index')->with('message', 'Faq Category Created Successfully.');
        } catch (Exception $e) {

            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit(string|int $id)
    {
        $category = $this->model->find($id);
        $category->status = ($category->status && ($category->status == 'active')) ? 1 : 0;
        return view('backend.faq-category.edit', ['cat' => $category, 'categories' => $this->model->latest('id')->get()]);
    }

    public function update(mixed $request, $id)
    {
        DB::beginTransaction();
        $faqCategory = $this->model->find($id);

        if (!$faqCategory) {
            return back()->with('error', 'Faq Category not found.');
        }

        $request->validate([
            'name' => 'required|max:99',
            'icon' => 'nullable|file|image',
            'sort_order' => 'nullable|numeric',
            'status' => 'required',
        ]);

        $status = $request->status == 1 ? 'active' : 'inactive';
        $slug = $faqCategory->name != $request->name ? Helpers::slug($this->model, $request->name) : $faqCategory->slug;

        try {
            $faqCategory->name = $request->name;
            $faqCategory->description = $request->description;
            $faqCategory->sort_order = $request->sort_order ?? 0;
            $faqCategory->status = $status;
            $faqCategory->slug = $slug;

            if ($request->hasFile('icon') && $request->file('icon')->isValid()) {
                $faqCategory->icon = Helpers::storeFile($request->file('icon'), $this->baseMediaPath, $faqCategory->icon);
            }

            $faqCategory->save();
            DB::commit();
            return redirect()->route('backend.faq-category.index')->with('message', 'Faq Category Created Successfully.');
        } catch (Exception $e) {

            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $category = $this->model->find($id);
            if (file_exists($category->icon)) {
                unlink($category->icon);
            }
            $category->delete();
            DB::commit();
            return redirect()->route('backend.faq-category.index')->with('message', 'Faq Category Deleted Successfully.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('backend.faq-category.index')->with('error', $e->getMessage());
        }
    }
}
