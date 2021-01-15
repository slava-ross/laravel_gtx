<?php

namespace App;

use Dadata\DadataClient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
//use Illuminate\Database\Eloquent\Builder;

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

    public function comments()
    {
        return $this->belongsToMany('App\Comment');
    }

    public static function getCityNameByIP($clientIPAddress)
    {
        $dadata = new DadataClient(config('services.dadata.token'), null);
        $response = $dadata->iplocate($clientIPAddress);
        return empty($response) ? NULL : $response['data']['city'];
    }

    public static function getCityByName($cityName)
    {
        $city = City::where('name', $cityName)->first();
        return $city;
    }

    public static function isCityStored()
    {
        $cityExist = City::where('name', $cityName)->count();
        return $cityExist ?? false;
    }

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
