<?php

namespace App\Http\Controllers\Backend;

use App\Models\FaqCategory;
use App\Http\Controllers\Controller;
use App\Repositories\Backend\FaqCategoryRepository;
use Illuminate\Http\Request;
use Auth;

class FaqCategoryController extends Controller
{
    public $repository;
    public function __construct(FaqCategoryRepository $repository)
    {
        $this->authorizeResource(FaqCategory::class, 'faq-category');
        $this->repository = $repository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->repository->index();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // return $this->repository->create($request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->repository->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(FaqCategory $faqCategory)
    {
        // return $this->repository->edit($faqCategory, $faqCategory?->id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return $this->repository->edit($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return $this->repository->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->repository->destroy($id);
    }
}
