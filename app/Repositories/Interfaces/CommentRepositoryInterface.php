<?php

namespace App\Repositories\Interfaces;

use App\Comment;

interface CommentRepositoryInterface
{
    public function getCommentsByCityName(string $cityName);
    public function getCommentsByCityId(int $cityId);
    public function getCommentsByAuthor(int $authorId);
    public function getCommentById(int $commentId);
    public function create(array $attributes);
    public function commentUpdate(Comment $comment);
}
