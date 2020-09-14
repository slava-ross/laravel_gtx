<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\City;
use Faker\Generator as Faker;

$factory->define(City::class, function (Faker $faker) {
    $created = $faker->dateTimeBetween('- 30 days', '-1 day');
    return [
        'name' => $faker->unique()->city,
        'created_at' => $created,
        'updated_at' => $created,
    ];
});
