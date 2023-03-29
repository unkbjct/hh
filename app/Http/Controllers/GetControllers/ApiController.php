<?php

namespace App\Http\Controllers\GetControllers;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function cityFind(Request $request)
    {
        $cities = City::where("city", "LIKE", "%{$request->city}%")
            ->orWhere("region", "LIKE", "%{$request->city}%")
            ->limit(10)
            ->get();
        return $cities;
    }

    public function citySet(Request $request, City $city)
    {
        // dd($city);
        return redirect()->back()->withCookie(cookie()->forever('city', json_encode([
            'id' => $city->id,
            'title' => ($city->city) ? $city->city : $city->region,
        ], JSON_UNESCAPED_UNICODE)));
        // return $city;
    }

    public function cityDefine(Request $request)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $data = \Location::get($ip);
        if (!$data) return redirect()->back()->withCookie(cookie()->forever('city', 'incorrect'));
        $lat = (int)($data->latitude);
        $long = (int)($data->longitude);
        $city = City::where('geo_lat', "LIKE", "{$lat}%")->where('geo_lon', "LIKE", "{$long}%")->first();
        if ($city) {
            return redirect()->back()->withCookie(cookie()->forever('city', json_encode([
                'id' => $city->id,
                'title' => ($city->city) ? $city->city : $city->region,
            ], JSON_UNESCAPED_UNICODE)));
        } else {
            return redirect()->back()->withCookie(cookie()->forever('city', 'incorrect'));
        }
    }
}
