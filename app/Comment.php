<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * Атрибуты для массового заполнения полей модели
     *
     * @var array
     */
    protected $fillable = [
        'title', 'comment_text', 'rating',
    ];
    /**
     * @var mixed
     */
    private $user_id;

    /**
     * Методы для связи отношений модели
     *
     */
    public function cities()
    {
        return $this->belongsToMany('App\City');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
