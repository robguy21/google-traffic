<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleMaps;

class TravelTime extends Controller
{
    public function get(Request $request, GoogleMaps $google_maps) {
        // always deal in lower case, there are cleaner ways of handling this
        // that I should probably do instead but this is dragging on :/
        $suburbs = array_map('strtolower', explode(',', $request->suburbs));
        $result = $google_maps->getTravelTimesFor($suburbs);

        return response()->json($result);
    }
}
