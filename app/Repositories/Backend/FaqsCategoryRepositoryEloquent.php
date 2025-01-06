<?php

namespace App\Repositories\Backend;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Backend\FaqsCategoryRepository;
use App\Entities\Backend\FaqsCategory;
use App\Validators\Backend\FaqsCategoryValidator;

/**
 * Class FaqsCategoryRepositoryEloquent.
 *
 * @package namespace App\Repositories\Backend;
 */
class FaqsCategoryRepositoryEloquent extends BaseRepository implements FaqsCategoryRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return FaqsCategory::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function index() {
        return 'Hello';
    }
    
}
