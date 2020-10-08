<?php

namespace App\Http\Controllers;

use App\City;
use App\Comment;
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
        //$ip_address = $request->ip();
        $ip_address = '78.85.1.5';
        $dadata = new \Dadata\DadataClient(getenv('DADATA_TOKEN'), null);
        $response = $dadata->iplocate($ip_address);
        $city_name = $response['value'];

        $cities = DB::table('cities')
            ->join('city_comment', 'cities.id', '=', 'city_comment.city_id')
            ->select('cities.*')
            ->distinct()
            ->get();

        //dd($cities);

        return view('cities.index', compact('city_name', 'cities'));
    }

}
