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
Route::post('/schools/filter', 'School\SchoolController@Filter');
Route::post('/schools/search', 'School\SchoolController@searchSchool');

Route::get('register/activate/{token}', 'AuthController@registerActivate');

<<<<<<< HEAD
=======
Route::post('password/reset', 'ForgetPasswordController@reset');



>>>>>>> db82c09ef15811aefa7cb19821ec0ea2c7dedb3d
Route::group(['middleware' => 'app_key'], function(){

    /*Auth System Routes: */
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('password/forget', 'ForgetPasswordController@forget');
    Route::post('password/reset', 'ForgetPasswordController@reset');

    /*School Routes*/
    Route::apiResource('/schools','School\schoolController')->parameters(['schools' => 'id',]);
    Route::post('/schools/{id}/facilities','School\schoolController@addSchoolFacility');
    Route::post('/schools/{id}/images','School\schoolController@uploadSchoolImage');
    Route::delete('/schools/{id}/facilities','School\schoolController@deleteSchoolFacility');
    Route::delete('/schools/{id}/images','School\schoolController@deleteSchoolImage');

    /*CommunityPosts Routes*/
    Route::post('/schools/{school_id}/CommunityPosts/update/{post_id}', 'Posts\CommunityPostsController@update');
    Route::get('/schools/{school_id}/CommunityPosts/My_Posts', 'Posts\CommunityPostsController@ShowPostsByUserID');
    Route::apiResource('/schools/{school_id}/CommunityPosts', 'Posts\CommunityPostsController');

    /*Review Routes*/
    Route::apiResource('/schools/{school_id}/Review', 'ReviewsController');

    /*comments on posts  Routes*/
    Route::get('/schools/{id}/CommunityPosts/{postid}/comments', 'Posts\LikesOfPostsController@index');//show comments on post
    Route::post('/schools/{id}/CommunityPosts/{postid}/Comments/{commentid}', 'Posts\CommentsOnPostsController@update');//update comment
    Route::post('/schools/{id}/CommunityPosts/{ppstid}/comments/{commentid}/delete', 'Posts\LikesOfPostsController@destroy');//delete comment

    /*Likes on posts Routes*/
    Route::get('/schools/{id}/CommunityPosts/{postid}/likes', 'Posts\LikesOfPostsController@index');//show likes on post

    /*Ads Routes*/
    Route::get('ads', 'AdsController@index');
    Route::get('ads/{id}', 'AdsController@show');
    Route::post('ads/store', 'AdsController@store')->middleware('admin');
    Route::post('ads/update/{id}', 'AdsController@update')->middleware('admin');
    Route::delete('ads/delete/{id}', 'AdsController@destroy')->middleware('admin');

    Route::group(['middleware' => 'auth:api'], function(){
        /*User's Profile Routes */
        Route::get('user','User\UserController@index');//getting all the users
        Route::get('user/profile','User\UserController@profile');//getting the user's profile 
        Route::post('user/update','User\UserController@update');//updating the user's profile
        Route::delete('user/delete','User\UserController@destroy');//deleting the user

        /*favourite schools Routes*/
        Route::post('user/favorites', 'User\UserController@getFavorites');
        Route::post('user/favorites/{school_id}/add', 'User\UserController@AddFavorites');
        Route::post('user/favorites/{school_id}/remove', 'User\UserController@RemoveFavorites');

        //logout
        Route::get('logout', 'AuthController@logout'); 
    });

    /*App admin Routes*/
    Route::group(['middleware' => ['auth:api','admin'] ], function(){
        Route::get('suggestions/','appAdminController@getNewSchoolSuggestions');
        Route::put('suggestions/{id}','appAdminController@approveSuggestion');
    });

});
