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

//Auth system routes
Route::post('login', 'AuthController@login');
Route::post('register', 'AuthController@register');
Route::get('register/activate/{token}', 'AuthController@registerActivate');

Route::group(['middleware' => 'auth:api'], function(){

    Route::apiResource('user','User\UserController')->middleware('verified');
    Route::apiResource('review','ReviewsController')->middleware('verified');

    //logout
    Route::get('logout', 'AuthController@logout'); 
});
