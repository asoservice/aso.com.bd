<?php

namespace App\Repositories\Backend;

use Carbon\Carbon;
use App\Models\FaqCategory;
use Illuminate\Http\Request;
use App\Helpers\ContentsLoader;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class FaqCategoryRepository.
 *
 * @package namespace App\Repositories\Backend;
 */
class FaqCategoryRepository extends BaseRepository
{
    // protected ContentsLoader $apps;

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
    // public string $baseMediaPath = 'storage/media/faq-category/';
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
        $app = $this->app()
            ->addRequest($request)
            ->addData('name', required: 'required|max:119')
            ->addData('description')
            ->addFile('icon', required: 'nullable|file|image')
            ->addData('status', required: 'required', customizeInput: fn($value) => $value == '1' ? 'active' : 'inactive')
            ->addSlug('name');

            return $app->storeData(true);
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
                    ->formOnly('update', $id, 'PUT');
            }
            return $this->app()->findAndSetData($id)->updateStatus(encoded: true);
        }

        return abort(404);
    }

    public function updateData(Request $request, $id)
    {
        $app = $this->app()
            ->findAndSetData($id)
            ->addRequest($request)
            ->addData('name', required: 'required|max:119', value: true)
            ->addData('description', value: true)
            ->addFile('icon', required: 'nullable|file|image', value: true)
            ->addData('status', value: true, required: 'required', customizeInput: fn($value) => $value ? 'inactive' : 'active')
            ->addSlug('name');

            return $app->updateData(encoded: true);
    }

    public function destroy($id)
    {
        return $this->app()->findAndSetData($id)->addDestroyingFile('icon')->destroyData(encoded: true);
    }
}
