<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\ServicesInterface;
use App\Services\Services;
use Illuminate\Http\Request;

class CityController extends Controller
{
    const CITY_COUNT_LIMIT = 12;

    /**
     * @var Services
     */
    private $service;

    /**
     * Конструктор
     * @param ServicesInterface $service
     */
    public function __construct(ServicesInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return
     */
    public function index(Request $request)
    {
        if ($request->session()->has('city_chosen')) {
            $city_name = $request->session()->get('city_chosen');

            // --- transit data to flashes ---
            $success = $request->session()->get('success') ?? NULL;
            $errors = $request->session()->get('errors') ?? NULL;

            return redirect()->route('comment.index', compact('city_name'))->with('success', $success)->with('errors', $errors);
        }
        else {
            $ip_address = $request->ip();

            // --- Заглушка для dev_mode ---
            if ($ip_address === "127.0.0.1") {
                 $ip_address = '78.85.1.5'; // Ижевск (Закомментировать в dev_mode для получения списка городов с отзывами)
            }

            $city_name = $this->service->takeCityNameByIP($ip_address);
            // --- Если не получилось определить город ---
            if(empty($city_name)) {
                $cities = $this->service->takeCitesOfComments();
            }
            else {
                // --- Модальное окно с подтверждением города ---
                $cities = $this->service->takeMostCommentedCitiesPrior(self::CITY_COUNT_LIMIT);
            }
            return view('cities.index', compact('city_name','cities'));
        }
    }
}
