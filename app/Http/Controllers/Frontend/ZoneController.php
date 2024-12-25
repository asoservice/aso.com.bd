<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Zone;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Integration\GoogleMap;
use App\Models\Role;
use MatanYadaev\EloquentSpatial\Objects\Point;

class ZoneController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getAddress(Request $request)
    {
        if ($request->lat && $request->lng) {
            $lat = (float) $request->lat;
            $lng = (float) $request->lng;
            $apiKey = env('GOOGLE_MAP_API_KEY');
            $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lng}&key={$apiKey}";
            if ($url) {
                $response = file_get_contents($url);
                if(head(json_decode($response))) {
                    $address = head(json_decode($response)->results)?->formatted_address;
                    $this->setZone($lat, $lng, $address);
                    return response()->json(json_decode($response));
                }
            }
        }
    }

    public function getZoneIds($latitude, $longitude)
    {
        $point = new Point($latitude, $longitude);
        return Zone::whereContains('place_points', $point)->pluck('id')?->toArray();
    }

    public function checkZone(Request $request)
    {
        $zoneIds = session('zoneIds', []);
        return response()->json(['zoneSet' => !empty($zoneIds), 'location' => session('location', [])]);
    }

    public function autoComplete(Request $request)
    {
        $googleMap = new GoogleMap;
        $location = $request->location;
        $autoCompleteAddress = $googleMap->getAutocompleteLocations($location);
        return response()->json($autoCompleteAddress);
    }

    public function getCoordinates(Request $request)
    {
        $googleMap = new GoogleMap;
        $placeId = $request->place_id;
        $response = $googleMap->getCoordinates($placeId);

        if(isset($response['status'])) {
            if ($response['status'] == 'OK') {
                $address = $response['result']['formatted_address'];
                $lng = $response['result']['geometry']['location']['lng'];
                $lat = $response['result']['geometry']['location']['lat'];
                $zoneIds = $this->setZone($lat, $lng, $address);
                return response()->json(['status' => 'OK', 'zoneIds'=> $zoneIds]);
            }
        }

        return response()->json(['error' => $response]);
    }

    public function setZone($lat, $lng, $address = null)
    {
        session(['location' => $address]);
        $zoneIds = $this->getZoneIds($lat, $lng);
        session(['zoneIds' => $zoneIds]);
        return $zoneIds;
    }
}
