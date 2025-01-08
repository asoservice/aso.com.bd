<?php

namespace App\Repositories\Backend;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Backend\FaqCategoryRepository;
use App\Models\FaqCategory;
use App\Helpers\Helpers;
use App\Validators\Backend\FaqCategoryValidator;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * Class FaqCategoryRepositoryEloquent.
 *
 * @package namespace App\Repositories\Backend;
 */
class FaqCategoryRepositoryEloquent extends BaseRepository implements FaqCategoryRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return FaqCategory::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function index() {
        $categories = $this->model->latest('id'); 

        if (!empty(request()->search)) {
            $categories->where('title','LIKE','%'.request()->search.'%');
        }

        return view('backend.faq-category.index', [
            'categories' => $categories->get(),
            'allparent' => []
         ]);
    }

    public function store($request) {
        // return $request->all();
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
                    'created_by' => $request->user()->id,
                ]
            );
            if ($request->hasFile('icon') && $request->file('icon')->isValid()) {
                $category->addMediaFromRequest('icon')->toMediaCollection('icon');
            }

            DB::commit();
            return redirect()->route('backend.faq-category.index')->with('message', 'Faq Category Created Successfully.');
        } catch (Exception $e) {

            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }
    
}
