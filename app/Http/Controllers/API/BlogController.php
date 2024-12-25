<?php

namespace App\Http\Controllers\API;

use App\Exceptions\ExceptionHandler;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Repositories\API\BlogRepository;
use Exception;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    protected $repository;

    public function __construct(BlogRepository $repository)
    {
        $this->authorizeResource(Blog::class, 'blog',[
            'except' => ['index','show'],
        ]);
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        try {
            return $this->repository->index($request);
        } catch (Exception $e) {

            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
