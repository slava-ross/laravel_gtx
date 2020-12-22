<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Self_;

class Comment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'comment_text', 'rating',
    ];

    public function cities()
    {
        return $this->belongsToMany('App\City');
        //return $this->belongsToMany('App\City', 'city_comment', 'comment_id', 'city_id'); //related; table; foreignPivotKey; relatedPivotKey.
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public static function getCommentsByCityName($cityName)
    {
        $city = City::where('name', $city_name)->first();
        $comments = self::getCommentsByCityId($city->id);
        return $comments;
    }

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

    public static function getCommentsByAuthor($authorId)
    {
        $comments = parent::join('users as u', 'user_id', '=', 'u.id')
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
                'u.phone'
            )
            ->where('u.id', '=', $authorId)
            ->orderBy('comments.created_at', 'desc')
            ->paginate(4);
        return $comments;
    }
}
