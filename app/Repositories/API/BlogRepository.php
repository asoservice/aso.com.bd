<?php

namespace App\Repositories\API;

use App\Models\Blog;
use Exception;
use Prettus\Repository\Eloquent\BaseRepository;

class BlogRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'title' => 'like',
    ];

    public function model()
    {
        return Blog::class;
    }

    function index($request) 
    {
        try {
            $blog = $this->model->where('status', true);
            $paginate = $request->input('paginate', $blog->count());
            $blogPosts = $blog->latest('created_at')->paginate($paginate);

            return response()->json(['success' => true, 'data' => $blogPosts]);    
        } catch (Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
