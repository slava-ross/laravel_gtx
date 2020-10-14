<?php

namespace App\Http\Controllers;

use App\City;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $ip_address = $request->ip();
        //$ip_address = '78.85.1.5'; // Ижевск
        $city = new City;
        $city_name = $city->getCityName($ip_address);
        if(empty($city_name)) {
            $cities = DB::table('cities')
                ->join('city_comment', 'cities.id', '=', 'city_comment.city_id')
                ->select('cities.*')
                ->distinct()
                ->get();
                return view('cities.index', compact('cities'));
        }
        else {
            return view('cities.index', compact('city_name'));
        }
    }
}
