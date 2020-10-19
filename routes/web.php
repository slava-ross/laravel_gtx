<?php

use Illuminate\Support\Facades\Route;

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'CityController@index')->name('/');
Route::resource('/comment','CommentController');
Route::get('/verify/{token}', 'Auth\RegisterController@verify')->name('register.verify');
Route::get('/get_captcha/{config?}', function (\Mews\Captcha\Captcha $captcha, $config = 'default') {
    return $captcha->src($config);
});
