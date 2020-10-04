<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\User::class, 5)->create();
        factory(\App\City::class, 10)->create();
        factory(\App\Comment::class, 20)->create();

        $comments = App\Comment::all();

        App\City::all()->each(function ($city) use ($comments) {
            $city->comments()->attach(
                $comments->random(rand(1, 20))->pluck('id')->toArray()
            );
        });
    }
}
