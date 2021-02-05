<?php

namespace App;


use Illuminate\Database\Eloquent\Model;


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

    /**
     * Метод для связи отношений модели
     *
     */
    public function comments()
    {
        return $this->belongsToMany('App\Comment');
    }
}
