<?php

namespace App\Repositories\Backend;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Backend\FaqRepository;
use App\Models\Faq;
use App\Validators\Backend\FaqValidator;

/**
 * Class FaqRepositoryEloquent.
 *
 * @package namespace App\Repositories\Backend;
 */
class FaqRepositoryEloquent extends BaseRepository implements FaqRepository
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
    
}
