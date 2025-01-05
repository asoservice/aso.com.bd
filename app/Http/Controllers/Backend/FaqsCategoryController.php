<?php

namespace App\Http\Controllers\Backend;

use App\Entities\Backend\FaqsCategoryRepository;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\FaqCategory;
use App\Repositories\Backend\FaqsCategoryRepositoryEloquent;

class FaqsCategoryController extends Controller
{
    public $repository;
    public function __construct(FaqsCategoryRepositoryEloquent $repository){
        $this->authorizeResource(FaqCategory::class, 'faq-categories');
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
