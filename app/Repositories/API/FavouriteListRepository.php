<?php

namespace App\Repositories\API;

use App\Enums\FavouriteListEnum;
use App\Exceptions\ExceptionHandler;
use App\Models\FavouriteList;
use Exception;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class FavouriteListRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'service.title' => 'like',
        'provider.name' => 'like',
    ];

    public function model()
    {
        return FavouriteList::class;
    }

    public function index($request)
    {
        $query = $this->model->where('consumer_id', auth()->user()->id);
        switch ($request->type) {
            case FavouriteListEnum::PROVIDER:
                $query->whereNotNull('provider_id');
                break;
            case FavouriteListEnum::SERVICE:
                $query->whereNotNull('service_id');
                break;
        }

        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('provider', function ($providerQuery) use ($searchTerm) {
                    $providerQuery->where('name', 'like', "%$searchTerm%");
                });

                $q->orWhereHas('service', function ($serviceQuery) use ($searchTerm) {
                    $serviceQuery->where('title', 'like', "%$searchTerm%");
                });
            });
        }

        return $query->get()->toArray();
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {

            switch ($request->type) {
                case FavouriteListEnum::PROVIDER:
                    $favouritelist = $this->model->create([
                        'provider_id' => $request->providerId,
                    ]);
                    break;
                case FavouriteListEnum::SERVICE:
                    $favouritelist = $this->model->create([
                        'service_id' => $request->serviceId,
                    ]);
                    break;
            }
            $data = $this->model->where('id', $favouritelist->id)->get();

            DB::commit();

            return response()->json([
                'data' => $data,
                'message' => 'Successfully added in Your Favourite List',
                'success' => true,
            ]);
        } catch (Exception $e) {

            DB::rollback();
            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($type, $id)
    {
        try {
            $favouriteList = FavouriteList::where('consumer_id', auth()->user()->id)
                ->where(function ($query) use ($type, $id) {
                    if ($type === FavouriteListEnum::PROVIDER) {
                        $query->where('provider_id', $id);
                    } elseif ($type === FavouriteListEnum::SERVICE) {
                        $query->where('service_id', $id);
                    }
                })
                ->get();

            if ($favouriteList->count() > 0) {
                $favouriteList->first()->delete();
            }

            return response()->json([
                'message' => 'Successfully Removed From Your Favorite List',
                'success' => true,
            ]);
        } catch (Exception $e) {

            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }
}
