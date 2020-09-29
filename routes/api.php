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
    Route::apiResource('/schools/{school_id}/Reviews', 'reviews\ReviewsController');

    Route::get ('Reviews/{review_id}/reviewLikes', 'reviews\LikesOfReviewsController@numOfLikes');//view num of likes.
    Route::post ('Reviews/{review_id}/reviewLikes/{user_id}', 'reviews\LikesOfReviewsController@addLikes');//add like.
    Route::delete('Reviews/{review_id}/reviewLikes/{user_id}/like/{like_id}', 'reviews\LikesOfReviewsController@removeLikes');//remove like.

     Route::get ('Reviews/{review_id}/reviewDislikes', 'reviews\DislikesOfReviewsController@numOfDislikes');//view num of dislikes.
    Route::post ('Reviews/{review_id}/reviewDislikes/{user_id}', 'reviews\DislikesOfReviewsController@addDislikes');//add dislike.
    Route::delete('Reviews/{review_id}/reviewLikes/{user_id}/dislike/{dislike_id}', 'reviews\LikesOfReviewsController@removeDislikes');// remove dislike.
     

    /*comments on posts  Routes*/
    Route::get('/CommunityPosts/{post_id}/comments', 'Posts\LikesOfPostsController@index');//show comments on post.
    Route::put('/CommunityPosts/{post_id}/Comments/{commentid}/update', 'Posts\CommentsOnPostsController@update');//update comment.
    Route::post('/CommunityPosts/{post_id}/Comments/store', 'Posts\CommentsOnPostsController@store');//store new comment.
    Route::delete('/CommunityPosts/{post_id}/comments/{commentid}/delete', 'Posts\LikesOfPostsController@destroy');//delete comment.

    /*Likes on posts Routes*/
    Route::get('CommunityPosts/{post_id}/likes', 'Posts\LikesOf PostsController@numOfLikes');//show num of likes on post
     Route::post ('CommunityPosts/{post_id}/addlike/{user_id}',  'Posts\LikesOfPostsController@addLike');//add  like.
    Route::delete('CommunityPosts/{post_id}/removeLike/{like_id}', 'Posts\LikesOfPostsController@removeLike');//remove like.

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
        Route::get('suggestions/','SuggestionsController@getNewSchoolSuggestions');
        Route::put('suggestions/{id}','SuggestionsController@approveSuggestion');
    });

});
