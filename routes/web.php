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

Route::get('/', function () {
    return view('welcome');
})->middleware('verified');

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');

<<<<<<< HEAD
=======
Route::get('user/{id}/favorites', 'User\UserController@getFavorites');
Route::get('user/{user_id}/favorites/{school_id}/add', 'User\UserController@AddFavorites');
Route::get('user/{user_id}/favorites/{school_id}/remove', 'User\UserController@RemoveFavorites');
Route::apiResource('/schools/{school_id}/CommunityPosts', 'Posts\CommunityPostsController');
Route::apiResource('/schools/{school_id}/Reviews', 'ReviewsController');
>>>>>>> 4333a1f8dfba37a239d7fd7705f0a9916b81582e
