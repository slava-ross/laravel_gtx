<?php

namespace App\Services;

use App\Http\Requests\CommentRequest;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use App\Repositories\Interfaces\CityRepositoryInterface;
use App\Services\Interfaces\ServicesInterface;
use App\Comment;
use Illuminate\Support\Facades\Storage;

class Services implements ServicesInterface
{
    private $commentRepository;
    private $cityRepository;

    public function __construct(CommentRepositoryInterface $commentRepository, CityRepositoryInterface $cityRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->cityRepository = $cityRepository;
    }

    public function takeCommentById(int $commentId)
    {
        return $this->commentRepository->getCommentById($commentId);
    }

    public function takeCityByName(string $cityName)
    {
        return $this->cityRepository->getCityByName($cityName);
    }

    public function rememberNewCity(array $attributes)
    {
        return $this->cityRepository->create($attributes);
    }

    public function takeCityById(int $cityId)
    {
        return $this->cityRepository->getCityById($cityId);
    }

    public function takeCommentsByCityId(int $cityId)
    {
        return $this->commentRepository->getCommentsByCityId($cityId);
    }

    public function createNewComment(CommentRequest $request)
    {
        $cities = $request->cities;
        $comment = new Comment;
        $comment->fill($request->all());
        $comment->user_id = \Auth::user()->id;

        if ($request->file('img')) {
            $path = Storage::putFile('public', $request->file('img'));
            $url = Storage::url($path);
            $comment->img = $url;
        }
        $citiesIdArray = []; // --- Массив для хранения id городов ---

        if (!empty($cities)) {
            foreach ($cities as $cityName){
                $city = $this->cityRepository->getCityByName($cityName);
                // --- Новый город ---
                if (empty($city)) {
                    $city = $this->commentRepository->create(['name' => $cityName]);
                }
                $citiesIdArray[] = $city->id;
            }
        }
        else { // --- Если пустой список городов - сохраняем комментарий для всех ---
            $cityItems = $this->cityRepository->allToArray('id');
            foreach($cityItems as $item){
                $citiesIdArray[] = $item['id'];
            }
        }
        foreach($citiesIdArray as $city_id) {
            $city = $this->cityRepository->getCityById($city_id);
            $city->comments()->save($comment);
        }
        return $comment;
    }

    public function updateComment(CommentRequest $request, int $commentId)
    {
        $comment = $this->commentRepository->getCommentById($commentId);
        $comment->fill($request->all());
        if (empty($request->img_leave)) {
            if ($request->file('img')) {
                $path = Storage::putFile('public', $request->file('img'));
                $url = Storage::url($path);
                $comment->img = $url;
            }
            else {
                $comment->img = null;
            }
        }
        return $this->commentRepository->commentUpdate($comment);
    }

    public function deleteComment(Comment $comment)
    {
        Storage::disk('public')->delete(basename($comment->img));
        return $comment->delete();
    }

    public function takeCommentsByAuthor(int $authorId)
    {
        return $this->commentRepository->getCommentsByAuthor($authorId);
    }

    public function takeCityNameByIP(string $ipAddress)
    {
        return $this->cityRepository->getCityNameByIP($ipAddress);
    }

    public function takeCitesOfComments()
    {
        return $this->cityRepository->getCitesOfComments();
    }

    public function takeMostCommentedCitiesPrior(int $cityCountLimit)
    {
        return $this->cityRepository->getMostCommentedCitiesPrior($cityCountLimit);
    }
}

