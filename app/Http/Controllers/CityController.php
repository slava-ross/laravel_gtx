<?php

namespace App\Http\Controllers;

use App\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        /*$comments = Comment::join('users', 'id_author', '=', 'users.id')
            ->orderBy('comments.created_at', 'desc')
            ->paginate(4);
        */

        $cities = City::find(1)->comments();
        dump($comments);

        return view('comments.index', compact('comments'));
    }

}
