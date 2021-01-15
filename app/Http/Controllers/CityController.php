<?php

namespace App\Http\Controllers;

use App\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->session()->has('city_chosen')) {
            $city_name = $request->session()->get('city_chosen');
            $city = City::getCityByName($city_name);
            // --- transit data to flashes ---
            $success = $request->session()->get('success') ?? NULL;
            $errors = $request->session()->get('errors') ?? NULL;
            return redirect()->route('comment.index', ['city_id' => $city->id, 'city_name' => $city_name])->with('success', $success)->with('errors', $errors);
        }
        else {
            $ip_address = $request->ip();
            // --- Заглушка для dev_mode ---
            /*
            if ($ip_address === "127.0.0.1") {
                 $ip_address = '78.85.1.5'; // Ижевск (Закомментировать в dev_mode для получения списка городов с отзывами)
            }
            */
            $city_name = City::getCityNameByIP($ip_address);
            // --- Если не получилось определить город ---
            if(empty($city_name)) {
                $cities = City::getCitesOfComments();
            }
            else {
                // --- Модальное окно с подтверждением города ---
                $cities = City::getMostCommentedCitiesPrior();
            }
            return view('cities.index', compact('city_name','cities'));
        }
    }
}
