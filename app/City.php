<?php

namespace App;

use Dadata\DadataClient;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public function comments()
    {
        return $this->belongsToMany('App\Comment');
    }

    public function getCityName($clientIPAddress) {
        $dadata = new DadataClient(config('services.dadata.token'), null);
        $response = $dadata->iplocate($clientIPAddress);
        if(empty($response)) {
            return null;
        }
        else {
            return $response['value'];
        }
    }
}
