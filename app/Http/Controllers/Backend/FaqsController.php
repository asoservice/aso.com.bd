<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\ContentsLoader;
use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Repositories\Backend\FaqRepository;
use Illuminate\Http\Request;

class FaqsController extends Controller
{
    private ContentsLoader $app;
    public $repository;

    public function __construct(FaqRepository $repository){
        $app = new ContentsLoader;
        $app->setModel(Faq::class);
        $app->addAssetPath('faq');
        $app->addViews('contents');
        $app->routeName('faq');
        $this->app = $app;

        // $this->authorizeResource(FaqCategory::class, 'faq-category');
        $this->repository = $repository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->repository->index($this->app);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->repository->createForm($this->app);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->repository->store($request, $this->app);
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
        return $this->repository->edit($this->app, $id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return $this->repository->updateData($request, $this->app, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->repository->destroy($this->app, $id);
    }
}
