<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::get('/', function () {
    return view('welcome');
});
*/
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/comment', 'CommentController@index')->name('comment.index');
Route::get('/comment/create', 'CommentController@create')->name('comment.create');
Route::get('/comment/show/{id}', 'CommentController@show')->name('comment.show');
Route::post('/comment', 'CommentController@store')->name('comment.store');
