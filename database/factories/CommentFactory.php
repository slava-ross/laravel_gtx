<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Comment;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    $title = $faker->realText(rand(10, 100));
    $comment_text = $faker->realText(rand(80, 255));
    $created = $faker->dateTimeBetween('- 30 days', '-1 day');
    return [
        'title' => $title,
        'user_id' => rand(1, 5),
        'rating' => rand(1, 5),
        'comment_text' => $comment_text,
        'created_at' => $created,
        'updated_at' => $created,
    ];
});
