<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    protected $model;

    public function __construct(Page $blog)
    {
        $this->model = $blog;
    }

    public function index(Request $request)
    {
        $pages = $this->model->where('status', true);
        $paginate = $request->input('paginate', $pages->count());

        return $pages->latest('created_at')->paginate($paginate);
    }
}
