<?php

namespace App\Http\Controllers\API;

use App\Enums\RoleEnum;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\CreateReviewRequest;
use App\Http\Requests\API\UpdateReviewRequest;
use App\Models\Review;
use App\Repositories\API\ReviewRepository;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public $repository;

    public $model;

    public function __construct(ReviewRepository $repository,Review $model)
    {
        $this->repository = $repository;
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $reviews = $this->filter($this->repository->with(['service', 'consumer', 'serviceman:id,name', 'provider:id,name']), $request);

        return $reviews->latest('created_at')->paginate($request->paginate ?? $this->repository->count());
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
    public function store(CreateReviewRequest $request)
    {
        return $this->repository->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReviewRequest $request,$id)
    {
        return $this->repository->update($request->all(), $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Review $review)
    {
        return $this->repository->destroy($review->id);
    }

    public function deleteAll(Request $request)
    {
        return $this->repository->deleteAll($request->id);
    }

    public function filter($reviews, $request)
    {
        $roleName = Helpers::getCurrentRoleName();
        if ($roleName == RoleEnum::PROVIDER) {
            $reviews = $reviews->where('provider_id', auth()->user()->id);
        }

        if ($roleName == RoleEnum::CONSUMER) {
            $reviews = $reviews->where('consumer_id', auth()->user()->id);
        }

        if ($request->service_id) {
            $reviews = $reviews->where('service_id', $request->service_id);
        }

        if ($request->field && $request->sort) {
            $reviews = $reviews->orderBy($request->field, $request->sort);
        }

        return $reviews;
    }
}
