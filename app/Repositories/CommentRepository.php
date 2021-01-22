<?php

namespace App\Repositories;

use App\City;
use App\Comment;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CommentRepository implements CommentRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Constructor
     */
    public function __construct(Comment $model)
    {
        $this->model = $model;
    }

    /**
     * Получение списка отзывов по имени города
     * @param $cityName
     * @return mixed
     */
    public function getCommentsByCityName($cityName)
    {
        $city = City::where('name', $cityName)->first();
        return $this->getCommentsByCityId($city->id);
    }

    /**
     * Получение списка отзывов по id города
     * @param $cityId
     * @return mixed
     */
    public function getCommentsByCityId($cityId)
    {
        return $this->model->join('users as u', 'user_id', '=', 'u.id')
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
    }

    /**
     * Получение списка отзывов по id автора (пользователя)
     * @param $authorId
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getCommentsByAuthor($authorId)
    {
        $names = DB::table('city_comment as cc')
            ->leftJoin('cities as ct', 'cc.city_id', '=', 'ct.id')
            ->select('comment_id', DB::raw("GROUP_CONCAT(ct.name SEPARATOR ', ') city_names"))
            ->groupBy('comment_id');

        return DB::table('comments as com')
            ->joinSub($names, 'names', function ($join) {
                $join->on('names.comment_id','=', 'com.id');
            })
            ->where('com.user_id', '=', $authorId)
            ->join('users as u', 'com.user_id', '=', 'u.id')
            ->addSelect('com.id as id','title','comment_text','rating','img','com.created_at','u.id as user_id','u.fio','u.email','u.phone','names.city_names')
            ->orderBy('com.created_at', 'desc')
            ->paginate(4);
    }

    /**
     * Выборка отзыва по его id
     * @param int $commentId
     * @return Comment
     */
    public function getCommentById($commentId)
    {
        return $this->model->find($commentId);
    }

    /**
     * Сохранение нового отзыва в хранилище
     *
     * @param array $attributes
     * @return Comment
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * Обновление отредактированного отзыва в хранилище
     *
     * @param array $attributes
     * @return Comment
     */
    public function commentUpdate($comment)
    {
        $comment->update();
        return $comment;
    }
}



