<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Helpers\Helpers;
use App\Models\Category;
use App\Enums\CategoryType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\API\CategoryRepository;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $repository;

    public $model;

    protected $service;

    public function __construct(CategoryRepository $repository)
    {
        $this->authorizeResource(Category::class, 'category', [
            'except' => ['index', 'show'],
        ]);

        $this->repository = $repository;
    }

    public function getAllCategories(Request $request)
    {
        try {

            $categories = $this->repository->where(['category_type' => CategoryType::SERVICE, 'status' => true]);
            if ($request->zone_ids) {
                $zone_ids = explode(',', $request->zone_ids);
                $categories = Helpers::getCategoriesByZoneIds($zone_ids);
            }

            return $categories = $categories->with(['hasSubCategories'])->latest('created_at')->paginate($request->paginate ?? $categories->count());

        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function index(Request $request)
    {
        try {

            $categories = $this->repository->where(['category_type' => CategoryType::SERVICE, 'status' => true])->whereNull('parent_id');
            if ($request->search) {
                $categories = $categories->where('title', 'like', '%'.$request->search.'%');
            }

            if ($request->providerId) {
                $providerId = $request->providerId;
                $categories = $categories->whereHas('services', function (Builder $services) use ($providerId) {
                    $services->whereHas('user', function (Builder $providers) use ($providerId) {
                        $providers->where('id', $providerId);
                    });
                });
            }

            if ($request->categoryId) {
                $categories = $categories->findOrFail($request->categoryId)->childs();
            }

            if ($request->zone_ids) {
                $zone_ids = explode(',', $request->zone_ids);
                $categories = $categories->whereRelation('zones', function ($zones) use ($zone_ids) {
                    $zones->WhereIn('zone_id', $zone_ids);
                });
            }

            return $categories = $categories->with(['hasSubCategories.hasSubCategories'])->latest('created_at')->paginate($request->paginate ?? $categories->count());

        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
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
    public function show($id)
    {
        return $this->repository->show($id);
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

    public function getCategoryCommission(Request $request)
    {
        try {
            return $this->repository->getCategoryCommission($request);

        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
