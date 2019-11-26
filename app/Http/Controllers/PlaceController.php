<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Place;
use App\User;

class PlaceController extends Controller
{
    public function createSource(Request $request, $source){
        $userId = $request['userId'];
        $placeId = $request['placeId'];

        $user = User::find($userId);
        $checkLike = $user->$source->contains(function ($place) use ($placeId) {
            return $place->place_id == $placeId;
        });
        $checkPlace = Place::where('place_id', $placeId)->first();

        if ($checkPlace === null) {
            $place = new Place();
            $place->place_id = $placeId;
            $place->save();
            $user->$source()->attach($place);
        } else {
            if ($checkLike) {
                $user->$source()->detach($checkPlace);
            } else {
                $user->$source()->attach($checkPlace);
            }
        }
    }

    public function getSource($source, $username){
        $user = User::where('username', $username)->first();
        $places = $user->$source;
        $finalPlaces = [];
        $googleKey = env("GOOGLE_API");

        foreach ($places as $place) {
            $url = "https://maps.googleapis.com/maps/api/place/details/json?placeid=" . $place->place_id . "&key=" . $googleKey;
            array_push($finalPlaces, $url);
        }

        $result = array();
        $curly = array();
        $mh = curl_multi_init();

        foreach ($finalPlaces as $id => $d) {
            $curly[$id] = curl_init();

            $url = $d;
            curl_setopt($curly[$id], CURLOPT_URL,            $url);
            curl_setopt($curly[$id], CURLOPT_HEADER,         0);
            curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curly[$id], CURLOPT_SSL_VERIFYPEER, 0);

            curl_multi_add_handle($mh, $curly[$id]);
        }

        $running = null;
        do {
            curl_multi_exec($mh, $running);
        } while($running > 0);

        foreach($curly as $id => $c) {
            $result[$id] = json_decode(curl_multi_getcontent($c));
            curl_multi_remove_handle($mh, $c);
        }

        curl_multi_close($mh);

        return response()->json([
            'sights' => $result
        ], 201);
    }
}
