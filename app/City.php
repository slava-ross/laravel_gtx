<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public function comments()
    {
        return $this->belongsToMany('App\Comment');
    }
}
