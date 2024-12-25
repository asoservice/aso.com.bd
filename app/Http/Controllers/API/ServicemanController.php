<?php

namespace App\Http\Controllers\API;

use App\Enums\RoleEnum;
use App\Exceptions\ExceptionHandler;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\CreateServicemanRequest;
use App\Http\Requests\API\UpdateServicemanRequest;
use App\Models\User;
use App\Repositories\API\ServicemanRepository;
use Exception;
use Illuminate\Http\Request;

class ServicemanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $repository;

    public function __construct(ServicemanRepository $repository)
    {
        $this->authorizeResource(User::class, User::class, [
            'except' => ['index', 'show'],
        ]);
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        try {

            $serviceman = $this->filter($this->repository->role(RoleEnum::SERVICEMAN), $request);

            return $serviceman->latest('created_at')->paginate($request->paginate ?? $serviceman->count());

        } catch (Exception $e) {

            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateServicemanRequest $request)
    {
        return $this->repository->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->repository->show($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServicemanRequest $request, $id)
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

    public function filter($serviceman, $request)
    {
        if (Helpers::isUserLogin()) {
            $roleName = Helpers::getCurrentRoleName();
            if ($roleName == RoleEnum::PROVIDER) {
                $serviceman = $serviceman->where('provider_id', Helpers::getCurrentProviderId());
            }
        }

        if ($request->provider_id) {
            $serviceman = $serviceman->where('provider_id', $request->provider_id);
        }

        if ($request->search) {
            $serviceman->where('name', 'like', '%'.$request->search.'%');
        }

        if ($request->rating) {
            $ratings = explode(',', $request->rating);
            $serviceman = $this->getServicemanByRating($ratings, $serviceman);
        }

        if ($request->id) {
            $serviceman = $serviceman->where('id', $request->id);
        }

        if ($request->field && $request->sort) {
            $serviceman = $serviceman->orderBy($request->field, $request->sort);
        }

        if ($request->experience) {
            $experienceCriteria = $request->experience;

            if ($experienceCriteria === 'low') {
                $serviceman = $serviceman
                    ->orderByRaw('CASE WHEN experience_interval = "months" THEN 1 ELSE 2 END ASC')
                    ->orderBy('experience_duration', 'asc');
            } elseif ($experienceCriteria === 'high') {
                $serviceman = $serviceman
                    ->orderByRaw('CASE WHEN experience_interval = "years" THEN 1 ELSE 2 END ASC')
                    ->orderBy('experience_duration', 'desc');
            }
        }

        return $serviceman->with('servicemanreviews', 'UserDocuments');
    }

    public function getServicemanByRating($ratings, $serviceman)
    {
        return $serviceman->where(function ($query) use ($ratings) {
            foreach ($ratings as $rating) {
                $query->orWhere(function ($query) use ($rating) {
                    $query->whereHas('reviews', function ($query) use ($rating) {
                        $query->select('serviceman_id')
                            ->groupBy('serviceman_id')
                            ->havingRaw('AVG(rating) >= ?', [$rating])
                            ->havingRaw('AVG(rating) < ?', [$rating + 1]);
                    });
                });
            }
        });
    }
}
