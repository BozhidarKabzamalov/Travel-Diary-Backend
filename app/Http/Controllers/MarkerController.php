<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Marker;
use App\User;

class MarkerController extends Controller
{
    public function createMarker(Request $request){
        $markerData = $request['marker'];
        $city = $request['city'];
        $country = $request['country'];
        $userId = $request['userId'];
        $placeId = $request['placeId'];

        $marker = new Marker();
        $marker->user_id = $userId;
        $marker->city = $city;
        $marker->country = $country;
        $marker->place_id = $placeId;
        $marker->lat = $markerData['lat'];
        $marker->lng = $markerData['lng'];

        $marker->save();

        return response()->json([
            'marker' => $marker,
        ], 201);
    }

    public function getMarkers(Request $request, $username){
        $user = User::where('username', $username)->first();
        $userId = $user->id;
        $markers = Marker::where('user_id', $userId)->groupBy('city', 'country')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'markers' => $markers,
        ], 201);
    }

    public function deleteMarker(Request $request){
        $markers = Marker::find($request['markerId']);
        $markers->delete();

        return response()->json([
            'message' => 'Successfully deleted marker!'
        ], 201);
    }

    public function getSights(Request $request, $lat, $lng, $type, $range, $pageToken = null) {
        $latLng = $lat . ',' . $lng;
        $googleKey = env("GOOGLE_API");

        if ($pageToken === null) {
            $get = file_get_contents("https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=" . $latLng . "&radius=" . $range . "&type=" . $type ."&key=" . $googleKey);
        } else {
            $get = file_get_contents("https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=" . $latLng . "&radius=" . $range . "&type=" . $type . "&key=" . $googleKey . "&pagetoken=" . $pageToken);
        }

        $result = json_decode($get);

        return response()->json([
            'result' => $result
        ], 201);
    }

    public function getSight(Request $request, $placeId, $userId = null) {
        $googleKey = env("GOOGLE_API");
        $get = file_get_contents("https://maps.googleapis.com/maps/api/place/details/json?placeid=" . $placeId . "&key=" . $googleKey);
        $result = json_decode($get);
        $checkIfVisited = null;
        $checkIfWishlisted = null;

        if ($userId != null) {
            $user = User::find($userId);
            $checkIfVisited = $user->visitedPlaces->contains(function ($place) use ($placeId) {
                return $place->place_id == $placeId;
            });
            $checkIfWishlisted = $user->wishlistedPlaces->contains(function ($place) use ($placeId) {
                return $place->place_id == $placeId;
            });
        }

        return response()->json([
            'result' => $result,
            'isVisited' => $checkIfVisited,
            'isWishlisted' => $checkIfWishlisted
        ], 201);
    }

}
