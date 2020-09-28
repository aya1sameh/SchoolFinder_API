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
//for forgetting the password page (to be changed in the front end)
Route::view('forgot_password', 'auth.reset_password')->name('password.reset');

//here i changed the login page that is redirected by the auth package.. 
Route::get('/login', function () {
    return view('notlogin');
})->name('login');



Route::apiResource('/schools/{school_id}/CommunityPosts', 'Posts\CommunityPostsController');
Route::post('/schools/{school_id}/CommunityPosts/update/{post_id}', 'Posts\CommunityPostsController@update');
Route::apiResource('/schools/{school_id}/Reviews', 'reviews\ReviewsController');
Route::apiResource('/schools/{id}/CommunityPosts/{pid}/CommentsOnPosts', 'Posts\CommentsOnPosts');
