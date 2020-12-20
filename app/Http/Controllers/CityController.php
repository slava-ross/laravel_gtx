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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        if ($request->session()->has('city_chosen')) {
            $city_name = $request->session()->get('city_chosen');
            $city = City::getCityByName($city_name);
            $success = $request->session()->get('success') ?? NULL; // transit data to flashes
            $errors = $request->session()->get('errors') ?? NULL;
            return redirect()->route('comment.index', ['city_id' => $city->id, 'city_name' => $city_name])->with('success', $success)->with('errors', $errors);
        }
        else {
            $ip_address = $request->ip();
            if ($ip_address === "127.0.0.1") { // Заглушка для dev_mode
                $ip_address = '78.85.1.5'; // Ижевск
            }
            $city_name = City::getCityNameByIP($ip_address);
            if(empty($city_name)) { // Не смогли определить город
                $cities = City::getCitesOfComments();
                return view('cities.index', compact('cities'));
            }
            else {
                // Модальное окно с подтверждением города
                return view('cities.index', compact('city_name'));
            }
        }
    }
}
