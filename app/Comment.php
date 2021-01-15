<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
//use phpDocumentor\Reflection\Types\Self_;

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

    /*
     * Методы для связи отношений модели
     */
    public function cities()
    {
        return $this->belongsToMany('App\City');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /*
     * Получение списка отзывов по имени города
     */
    public static function getCommentsByCityName($cityName)
    {
        $city = City::where('name', $city_name)->first();
        $comments = self::getCommentsByCityId($city->id);
        return $comments;
    }

    /*
     * Получение списка отзывов по id города
     */
    public static function getCommentsByCityId($cityId)
    {
        $comments = parent::join('users as u', 'user_id', '=', 'u.id')
            ->join('city_comment as cc', 'comments.id', '=', 'cc.comment_id')
            ->leftJoin('cities as c', 'city_id', '=', 'c.id')
            ->select(
                'comments.id',
                'title',
                'comment_text',
                'rating',
                'img',
                'comments.created_at',
                'user_id',
                'u.fio',
                'u.email',
                'u.phone',
                'c.id as city_id',
                'c.name'
            )
            ->where('c.id', '=', $cityId)
            ->orderBy('comments.created_at', 'desc')
            ->paginate(4);
        return $comments;
    }

    /*
     * Получение списка отзывов по id автора (пользователя)
     */
    public static function getCommentsByAuthor($authorId)
    {
        $commentsRaw = DB::select(
            DB::raw("WITH ccc AS (
                SELECT comment_id, GROUP_CONCAT(ct.name SEPARATOR ', ') names
                FROM city_comment cc
                JOIN cities ct
                ON cc.city_id = ct.id
                GROUP BY comment_id
                )
                SELECT
                    com.id AS id,
                    title,
                    comment_text,
                    rating,
                    img,
                    com.created_at,
                    u.id AS user_id,
                    u.fio,
                    u.email,
                    u.phone,
                    names
                FROM comments com
                JOIN users u
                ON com.user_id = u.id
                JOIN ccc
                ON ccc.comment_id = com.id
                WHERE u.id = $authorId
                ORDER BY com.created_at DESC"
            )
        );

        $comments = collect($commentsRaw);
        return $comments;
    }
}
