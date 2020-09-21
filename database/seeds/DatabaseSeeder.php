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
        factory(\App\User::class, 10)->create();
        factory(\App\City::class, 15)->create();
        factory(\App\Comment::class, 30)->create();

        $comments = App\Comment::all();

        App\City::all()->each(function ($city) use ($comments) {
            $city->comments()->attach(
                $comments->random(rand(1, 30))->pluck('id')->toArray()
            );
        });

        /*
         public function run()
        {
           factory(App\User::class,3)->create();
           $roles = factory(App\Role::class,3)->create();
           App\User::All()->each(function ($user) use ($roles){
              $user->roles()->saveMany($roles);
           });
        }
         */
    }
}
