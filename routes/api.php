<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Auth System Routes: */
Route::post('login', 'AuthController@login');
Route::post('register', 'AuthController@register');
Route::get('register/activate/{token}', 'AuthController@registerActivate');
Route::post('password/forget', 'ForgetPasswordController@forget');
Route::post('password/reset', 'ForgetPasswordController@reset');

/*School Routes*/
Route::apiResource('/schools','School\schoolController');
Route::post('/schools/{id}/facilities','School\schoolController@addSchoolFacility');
Route::post('/schools/{id}/images','School\schoolController@uploadSchoolImage');
Route::delete('/schools/{id}/facilities','School\schoolController@deleteSchoolFacility');
Route::delete('/schools/{id}/images','School\schoolController@deleteSchoolImage');

Route::apiResource('/schools/{school_id}/CommunityPosts', 'Posts\CommunityPostsController');
Route::apiResource('/schools/{school_id}/Review', 'ReviewsController');



Route::group(['middleware' => 'auth:api'], function(){

    /*User's Profile Routes */
    Route::get('user','User\UserController@index');//getting all the users
    Route::post('user/profile','User\UserController@profile');//getting the user's profile 
    Route::post('user/update','User\UserController@update');//updating the user's profile
    Route::delete('user/delete','User\UserController@destroy');//deleting the user

    /*favourite schools Routes*/
    Route::post('user/favorites', 'User\UserController@getFavorites');
    Route::post('user/favorites/{school_id}/add', 'User\UserController@AddFavorites');
    Route::post('user/favorites/{school_id}/remove', 'User\UserController@RemoveFavorites');

    //logout
    Route::get('logout', 'AuthController@logout'); 
});
