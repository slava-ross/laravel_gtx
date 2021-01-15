<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    /**
     * Атрибуты для массового заполнения полей модели
     *
     * @var array
     */
    protected $fillable = [
        'fio', 'email', 'phone', 'password', 'verify_token', 'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*
     * Метод для связи отношений модели
     */
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
}
