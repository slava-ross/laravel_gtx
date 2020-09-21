<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function city()
    {
        return $this->belongsToMany('App\City', 'id_city', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'id_author', 'id');
    }
}
