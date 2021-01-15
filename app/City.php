<?php

namespace App;

use Dadata\DadataClient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class City extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /*
     * Метод для связи отношений модели
     */
    public function comments()
    {
        return $this->belongsToMany('App\Comment');
    }

    /*
     * Определение имени города по IP-адресу
     */
    public static function getCityNameByIP($clientIPAddress)
    {
        $dadata = new DadataClient(config('services.dadata.token'), null);
        $response = $dadata->iplocate($clientIPAddress);
        return empty($response) ? NULL : $response['data']['city'];
    }

    /*
     * Получение объекта "Город" по имени города
     */
    public static function getCityByName($cityName)
    {
        $city = City::where('name', $cityName)->first();
        return $city;
    }

    /*
     * Проверка наличия города в БД
     */
    public static function isCityStored()
    {
        $cityExist = City::where('name', $cityName)->count();
        return $cityExist ?? false;
    }

    /*
     * Получение списка городов с отзывами
     */
    public static function getCitesOfComments()
    {
        $cities = DB::table('cities')
            ->join('city_comment', 'cities.id', '=', 'city_comment.city_id')
            ->select('cities.id', 'cities.name')
            ->distinct()
            ->orderBy('cities.name', 'asc')
            ->get();
        return  $cities ?? NULL;
    }

    /*
     * Получение списка городов с количеством отзывов по каждому с сортировкой по убыванию количества отзывов
     * Лимит - 12 городов
     */
    protected static function getMostCommentedCitiesPrior()
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
            ->limit(12)
            ->get();

        return  $cities ?? NULL;
    }
}
