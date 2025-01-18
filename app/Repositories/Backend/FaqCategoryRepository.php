<?php

namespace App\Repositories\Backend;

use App\Helpers\ContentsLoader;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\FaqCategory;
use App\Helpers\Helpers;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * Class FaqCategoryRepository.
 *
 * @package namespace App\Repositories\Backend;
 */
class FaqCategoryRepository extends BaseRepository
{
    // protected ContentsLoader $app;

    // public function __construct()
    // {
    //     $app = new ContentsLoader;
    //     $app->setModel(FaqCategory::class);
    //     $app->addAssetPath('faq-category');
    //     $app->addViews('contents');
    //     $app->routeName('faq-category');
    //     $this->app = $app;
    // }

    public function app(){
        $app = new ContentsLoader;

        $app->setModel(FaqCategory::class)
            ->addAssetPath('faq-category')
            ->addViews('contents')
            ->routeName('faq-category');
        
        return $app;
    }

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
        return $this->app()
            ->initDataTable()
            ->addCheckBoxColumn()
            ->addImageColumn('Icon', 'icon')
            ->addColumn('Name', 'name')
            ->addColumn('Create Date', 'created_at', function ($row) {
                return Carbon::parse($row->created_at)->diffForHumans();
            })
            ->addStatusColumn()
            ->addActionColum()
            ->renderDataTable();

        // $categories = $this->model->latest('id');

        // if (!empty(request()->search)) {
        //     $categories->where('title', 'LIKE', '%' . request()->search . '%');
        // }

        // return view('backend.faq-category.index', [
        //     'categories' => $categories->get(),
        //     'allparent' => []
        // ]);
    }

    public function createForm() {
        return $this->app()
            ->addInput('Name', 'name') 
            ->addInput('Description', 'description', 'description')
            ->addFileInput('Icon', 'icon')
            ->addSwitchInput('Status', 'status', 'on')
            ->formOnly('store');
    }

    public function store($request)
    {
        // return $request->all();
        return $this->app()
            ->addRequest($request)
            ->addData('name', required: 'required|max:119')
            ->addData('description')
            ->addFile('icon', required: 'file|image')
            ->addData('status', required: 'required', customizeInput: fn($value) => $value == '1' ? 'active' : 'inactive')
            ->addSlug('name')
            ->storeData()
        ;
        // DB::beginTransaction();

        // $request->validate([
        //     'name' => 'required|max:99',
        //     'icon' => 'nullable|file|image',
        //     'sort_order' => 'nullable|numeric',
        //     'status' => 'required',
        // ]);

        // $status = $request->status == 1 ? 'active' : 'inactive';
        // $slug = Helpers::slug($this->model, $request->name);

        // try {
        //     $category = $this->model->create(
        //         [
        //             'name' => $request->name,
        //             'description' => $request->description,
        //             'sort_order' => $request->sort_order ?? 0,
        //             'status' => $status,
        //             'slug' => $slug,
        //             'icon' => $request->hasFile('icon') ? Helpers::storeFile($request->file('icon'), $this->baseMediaPath) : null
        //         ]
        //     );

        //     DB::commit();
        //     return redirect()->route('backend.faq-category.index')->with('message', 'Faq Category Created Successfully.');
        // } catch (Exception $e) {

        //     DB::rollback();
        //     return back()->with('error', $e->getMessage());
        // }
    }

    public function edit(string $id)
    {
        if(request()->ajax()) {
            if(request()->has('editForm') && request()->get('editForm')) {
                return $this->app()
                    ->findAndSetData($id)
                    ->addInput('Name', 'name', value: true)
                    ->addInput('Description', 'description', 'description', value: true)
                    ->addFileInput('Icon', 'icon', value: true)
                    ->addSwitchInput('Status', 'status', value: true)
                    ->formOnly('update', $id);
            }
            return $this->app()->findAndSetData($id)->updateStatus(encoded: true);
        }

        return abort(404);
        /* $category = $this->model->find($id);
        $category->status = ($category->status && ($category->status == 'active')) ? 1 : 0;
        return view('backend.faq-category.edit', ['cat' => $category, 'categories' => $this->model->latest('id')->get()]); */
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
