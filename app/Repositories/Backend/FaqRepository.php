<?php

namespace App\Repositories\Backend;

use App\Helpers\ContentsLoader;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Validators\Backend\FaqValidator;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class FaqRepositoryEloquent.
 *
 * @package namespace App\Repositories\Backend;
 */
class FaqRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Faq::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function statusOptions(){
        return ['published'=> 'Published', 'draft'=> 'Draft', 'archived'=> 'Archived'];
    }

    public function index(ContentsLoader $app){
        return $app
            ->initDataTable()
            ->addCheckBoxColumn()
            ->addColumn('Question', 'question')
            ->addColumn('Sort Order', 'sort_order')
            ->addSelectColumn(options: $this->statusOPtions())
            ->addColumn('Helpful Votes', 'helpful_votes')
            ->addColumn('Not Helpful Votes', 'not_helpful_votes')
            ->addColumn('Create Date', 'created_at', function ($row) {
                return Carbon::parse($row->created_at)->diffForHumans();
            })
            ->addActionColum()
            ->renderDataTable(); 
    }

    public function createForm(ContentsLoader $app) {
        return $app
            ->addInput('Question', 'question', required: true) 
            ->addInput('Sort Order', 'sort_order', 'number')
            ->addSelectInput('Status', 'status', $this->statusOPtions(), placeholder: 'Select Status')
            ->addSelectInput('Faq Category', 'category_id', function ($select) {
                $select->data = FaqCategory::latest('id')->get(['name', 'id']);
                $select->value = 'name';
                return $select;
            }, required: true)
            ->addInput('Answer', 'answer', 'description', required: true)
            ->formOnly('store');
    }

    public function store(Request $request, ContentsLoader $app)
    {
        $app->addRequest($request)
            ->addData('question', required: 'required|max:299')
            ->addData('answer', required: 'required')
            ->addData('status', required: 'required')
            ->addData('sort_order')
            ->addData('category_id', required: 'required')
            ->addCreator();
        return $app->storeData(true);
    }

    public function edit(ContentsLoader $app, string $id)
    {
        if(request()->ajax()) {
            if(request()->has('editForm') && request()->get('editForm')) {
                return $app
                    ->findAndSetData($id)
                    ->addInput('Question', 'question', required: true, value: true) 
                    ->addInput('Sort Order', 'sort_order', 'number', value: true)
                    ->addSelectInput('Status', 'status', $this->statusOPtions(), placeholder: 'Select Status', value: true)
                    ->addSelectInput('Faq Category', 'category_id', function ($select) {
                        $select->data = FaqCategory::latest('id')->get(['name', 'id']);
                        $select->value = 'name';
                        return $select;
                    }, required: true, value: true)
                    ->addInput('Answer', 'answer', 'description', required: true, value: true)
                    ->formOnly('update', $id, 'PUT');
            }

            if(request()->has('key') && request()->has('value')) {       
                return $app->findAndSetData($id)->updateStatus(request()->get('key'), request()->get('value'), isCreator: true, encoded: true);
            }

            return $app->findAndSetData($id)->updateStatus(isCreator: true, encoded: true);
        }

        return abort(404);
    }

    public function updateData(Request $request, ContentsLoader $app, $id)
    {
        $app = $app
            ->findAndSetData($id)
            ->addRequest($request)
            ->addData('question', required: 'required|max:299')
            ->addData('answer', required: 'required')
            ->addData('status', required: 'required')
            ->addData('sort_order')
            ->addData('category_id', required: 'required');

            return $app->updateData(isCreator: true, encoded: true);
    }

    public function destroy(ContentsLoader $app, $id)
    {
        return $app->findAndSetData($id)->destroyData(encoded: true);
    }
    
}
