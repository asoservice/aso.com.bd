<?php

namespace App\Repositories\Frontend;

use App\Enums\ServiceTypeEnum;
use App\Models\Service;
use Prettus\Repository\Eloquent\BaseRepository;

class ServiceRepository extends BaseRepository
{
  public function model()
  {
    return Service::class;
  }

  public function details($slug)
  {
    $service = $this->model->where('slug', $slug)->with('user')->whereNull('deleted_at')?->where('type', ServiceTypeEnum::FIXED)?->first();
    $recentService = $this->model->whereNot('id', $service?->id)?->whereNull('deleted_at')?->whereNull('parent_id')?->where('type', ServiceTypeEnum::FIXED)->latest()?->paginate(4);
    return view('frontend.service.details', ['service' => $service, 'recentService' => $recentService]);
  }

  public function search($request)
  {
    $services = $this->model->where('title', 'like', '%'.$request->term.'%')
        ->whereNull('deleted_at')?->whereNull('parent_id')?->where('type', ServiceTypeEnum::FIXED)->limit(10)->get()
        ->map(function($service) {
            return [
                'slug' => $service->slug,
                'title' => $service->title,
                'image' => $service?->media?->first()?->getUrl() ?? asset('frontend/images/placeholder.png')
            ];
        });;

    return response()->json($services);
  }
}
