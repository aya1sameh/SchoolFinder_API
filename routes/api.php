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

/*School Routes*/
Route::apiResource('/schools','School\schoolController');
Route::post('/schools/{id}/facilities','School\schoolController@addSchoolFacility');
Route::delete('/schools/{id}/facilities','School\schoolController@deleteSchoolFacility');
Route::post('/schools/{id}/images','School\schoolController@uploadSchoolImage');


Route::apiResource('/schools/{school_id}/CommunityPosts', 'Posts\CommunityPostsController');

Route::group(['middleware' => 'auth:api'], function(){

    Route::apiResource('user','User\UserController'); 

    

    Route::apiResource('/schools/{school_id}/Review', 'ReviewsController');


    //logout
    Route::get('logout', 'AuthController@logout'); 
});

//just for testing that we can send diff responses acc. to the user's role:
Route::get('test/{id}','User\UserController@test');