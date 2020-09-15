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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
})->middleware('verified');

/*School Routes*/
Route::apiResource('/schools','School\schoolController');
Route::post('/schools/{id}/facilities','School\schoolController@addSchoolFacility');

Route::apiResource('user','User\UserController')->middleware('client');

Route::apiResource('review','ReviewsController')->middleware('client');

