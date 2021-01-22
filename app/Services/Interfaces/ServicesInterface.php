<?php

namespace App\Services\Interfaces;

use App\Comment;
use App\Http\Requests\CommentRequest;

interface ServicesInterface
{
    public function takeCommentById(int $id);
    public function takeCityByName(string $cityName);
    public function rememberNewCity(array $cityNames);
    public function takeCityById(int $cityId);
    public function createNewComment(CommentRequest $request);
    public function takeCommentsByCityId(int $cityId);
    public function updateComment(CommentRequest $request, int $commentId);
    public function deleteComment(Comment $comment);
    public function takeCommentsByAuthor(int $authorId);
    public function takeCityNameByIP(string $ipAddress);
    public function takeCitesOfComments();
    public function takeMostCommentedCitiesPrior(int $cityCountLimit);
}
