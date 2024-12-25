<?php

namespace App\Repositories\Backend;

use App\Models\Zone;
use Exception;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use Prettus\Repository\Eloquent\BaseRepository;

class ZoneRepository extends BaseRepository
{
    public function model()
    {
        return Zone::class;
    }

    public function create($attribute = [])
    {
        return view('backend.zone.create');
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {

            $coordinates = json_decode($request?->place_points ?? '', true);
            $points = array_map(function ($coordinate) {
                return new Point($coordinate['lat'], $coordinate['lng']);
            }, $coordinates);

            if (head($points) != $points[count($points) - 1]) {
                $points[] = head($points);
            }

            $lineString = new LineString($points);
            $place_points = new Polygon([$lineString]);
            $this->model->create([
                'name' => $request->name,
                'place_points' => $place_points,
                'locations' => $coordinates,
                'status' => $request->status,
            ]);

            DB::commit();

            return redirect()->route('backend.zone.index')->with('message', __('static.zone.created'));

        } catch (Exception $e) {

            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $zone = $this->model->findOrFail($id);
        $coordinates = $zone->place_points ? json_decode($zone->place_points) : null;

        return view('backend.zone.edit', ['coordinates' => $coordinates, 'zone' => $zone]);
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {

            $zone = $this->model->findOrFail($id);
            if (isset($request['place_points']) && ! empty($request['place_points'])) {
                $coordinates = json_decode($request['place_points'] ?? '', true);
                $points = array_map(function ($coordinate) {
                    return new Point($coordinate['lat'], $coordinate['lng']);
                }, $coordinates);

                if (head($points) != $points[count($points) - 1]) {
                    $points[] = head($points);
                }

                $lineString = new LineString($points);
                $place_points = new Polygon([$lineString]);
                unset($request['place_points']);

                $zone->place_points = $place_points;
                $zone->locations = $coordinates;
                $zone->save();
            }

            $zone->update($request);
            DB::commit();

            return redirect()->route('backend.zone.index')->with('message', __('static.zone.updated'));

        } catch (Exception $e) {

            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $zone = $this->model->findOrFail($id);
            $zone->destroy($id);

            DB::commit();

            return redirect()->back()->with(['message' => __('static.zone.deleted')]);

        } catch (Exception $e) {

            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function status($id, $status)
    {
        try {

            $tag = $this->model->findOrFail($id);
            $tag->update(['status' => $status]);

            return json_encode(['resp' => $tag]);
        } catch (Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }

    public function deleteAll($ids)
    {
        DB::beginTransaction();
        try {

            $this->model->whereNot('system_reserve', true)->whereIn('id', $ids)->delete();

            return back()->with('message', __('static.zone.deleted'));

        } catch (Exception $e) {

            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }
}
