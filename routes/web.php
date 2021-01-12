<?php

use Illuminate\Support\Facades\Route;

Auth::routes();
Route::get('/', 'CityController@index')->name('/');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/city', 'CityController@index')->name('/');
Route::resource('/comment','CommentController');
Route::get('/comment/author/{id}', 'CommentController@getAuthorsComments')->name('comment.author');
Route::get('/verify/{token}', 'Auth\RegisterController@verify')->name('register.verify');
Route::get('/get_captcha/{config?}', function (\Mews\Captcha\Captcha $captcha, $config = 'default') {
    return $captcha->src($config);
});
Route::get('/ajax/{city_name}', 'AjaxController@ajaxChooseCity');//->name('ajax.city');

/*
Route::resource('/comment', 'CommentController');

Route::get('comment', 'CommentController@index')->name('comment.index');
Route::get('comment/create', 'CommentController@create')->name('comment.create');
Route::get('comment/show/{id}', 'CommentController@show')->name('comment.show');
Route::get('comment/edit/{id}', 'CommentController@edit')->name('comment.edit');
Route::post('comment', 'CommentController@store')->name('comment.store');
Route::patch('comment/{id}', 'CommentController@update')->name('comment.update');
Route::delete('comment/{id}', 'CommentController@destroy')->name('comment.destroy');
*/
