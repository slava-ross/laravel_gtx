<?php

namespace App\Http\Controllers;

use App\City;
use App\Comment;

use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function ajaxGetCities()
    {
        $city = new City;
        $cities = $city->getCitesOfComments();

        dd($cities);
        //json_encode( массив_объектов );
        return $data;
    }

    public function ajaxChooseCity($cityName)
    {
        /*
        $res = Article::create(['title' => $request->title, 'text' => $request->text]);
        $data = ['id' => $res->id, 'title' => $request->title, 'text' => $request->text];

        json_encode(array_values($categoryList->toArray()));

        */
        $comments = Comment::getCommentsByCityName($cityName)->toJson();
        return $comments;
    }
}
