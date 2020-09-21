<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Comment;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    $title = $faker->realText(rand(10, 32));
    $comment_text = $faker->realText(rand(100, 500));
    $created = $faker->dateTimeBetween('- 30 days', '-1 day');
    return [
        'title' => $title,
        //'id_city' => rand(1, 15),
        'user_id' => rand(1, 10),
        'rating' => rand(1, 10),
        'comment_text' => $comment_text,
        'created_at' => $created,
        'updated_at' => $created,
    ];
});
