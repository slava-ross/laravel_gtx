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
        $cities = DB::table('cities')
            ->join('city_comment', 'cities.id', '=', 'city_comment.city_id')
            ->select('cities.*')
            ->distinct()
            ->get();

        //dd($cities);

        return view('cities.index', compact('cities'));
    }

}
