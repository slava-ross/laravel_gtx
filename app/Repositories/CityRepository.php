<?php

namespace App\Repositories;

use App\City;
use App\Repositories\Interfaces\CityRepositoryInterface;
use Dadata\DadataClient;
use Illuminate\Support\Facades\DB;

class CityRepository implements CityRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Constructor
     */
    public function __construct(City $model)
    {
        $this->model = $model;
    }

    /**
     * Определение имени города по IP-адресу
     *
     * @param $clientIPAddress
     * @return mixed|null
     */
    public function getCityNameByIP($clientIPAddress)
    {
        $dadata = new DadataClient(config('services.dadata.token'), null);
        $response = $dadata->iplocate($clientIPAddress);
        return empty($response) ? NULL : $response['data']['city'];
    }

    /**
     * Получение объекта "Город" по имени города
     *
     * @param $cityName
     * @return mixed
     */
    public function getCityByName($cityName)
    {
        return $this->model->where('name', $cityName)->first();
    }

    /**
     * Получение списка городов с отзывами
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCitesOfComments()
    {
        $cities = DB::table('cities')
            ->join('city_comment', 'cities.id', '=', 'city_comment.city_id')
            ->select('cities.id', 'cities.name')
            ->distinct()
            ->orderBy('cities.name', 'asc')
            ->get();
        return  $cities ?? NULL;
    }

    /**
     * Получение списка городов с количеством отзывов по каждому с сортировкой по убыванию количества отзывов
     *
     * @param $limit
     * @return \Illuminate\Support\Collection
     */
    public function getMostCommentedCitiesPrior($limit)
    {
        $cities = DB::table('cities')
            ->select('id', 'name', 'cnt')
            ->leftJoin(DB::raw
            ('(
                    SELECT city_id, count(*) cnt
                    FROM city_comment
                    GROUP BY city_id
                    ) cc'
            ),
                function($join)
                {
                    $join->on('cities.id', '=', 'cc.city_id');
                }
            )
            ->orderBy('cnt', 'desc')
            ->limit($limit)
            ->get();

        return  $cities ?? NULL;
    }

    /**
     * Сохранение нового города в БД
     *
     * @param array $attributes
     * @return City
     */
    public function create($attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * Выборка города по его id
     * @param int $cityId
     * @return mixed
     */
    public function getCityById($cityId)
    {
        return $this->model->find($cityId);
    }

    /**
     * Выборка указанного поля по всем городам
     * @param string $field
     * @return array
     */
    public function allToArray($field) {
        return $this->model->all($field)->toArray();
    }
}
