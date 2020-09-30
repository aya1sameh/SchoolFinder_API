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

    /*Search and filter */
    Route::post('/schools/filter', 'School\SchoolController@Filter');
    Route::post('/schools/search', 'School\SchoolController@searchSchool');

    /*CommunityPosts Routes*/
    Route::post('/schools/{school_id}/community_posts/{post_id}', 'Posts\CommunityPostsController@update');
    Route::get('/schools/{school_id}/community_posts/my_posts', 'Posts\CommunityPostsController@ShowPostsByUserID');
    Route::apiResource('/schools/{school_id}/community_posts', 'Posts\CommunityPostsController');

    /*Review Routes*/
    Route::apiResource('/schools/{school_id}/reviews', 'reviews\ReviewsController');

    //TODO::remove user id from endpoint and use token instead->won't use user_id :)
    //add numberofLikes& dislikes to review object->done!
    //delete: post_id={post_id} && user_id={request->user->id} to avoid a user deleting another user's like->done
    Route::post ('/schools/{school_id}/reviews/{review_id}/likes', 'reviews\LikesOfReviewsController@Likes');//add or remove likes on reviews.
    Route::post ('/schools/{school_id}/reviews/{review_id}/dislikes', 'reviews\DislikesOfReviewsController@Dislikes');//add or remove dislikes on reviews..
   
     
    /*comments on posts  Routes*/
    //NOTE::YOU CANNOT DELETE OR UPDATE A COMMENT UNLESS IT"S YOURS->done
    
    Route::apiResource('/schools/{school_id}/community_posts/{post_id}/comments', 'Posts\LikesOfPostsController');
    

    /*Likes on posts Routes*/
    //TODO:: show users instead of number of likes + Number of likes is an attribute in the post->done
    //delete: post_id={post_id} && user_id={request->user->id} to avoid a user deleting another user's like->done
    Route::get('/schools/{school_id}/community_posts/{post_id}/likes', 'Posts\LikesOfPostsController@ShowLikes');//show likes.
    Route::post('/schools/{school_id}/community_posts/{post_id}/likes', 'Posts\LikesOfPostsController@addOrRemoveLike');//show num of likes on post
  
    /*Ads Routes*/
    Route::post('/ads/{id}', 'AdsController@update');
    Route::apiResource('/ads', 'AdsController');

    Route::group(['middleware' => 'auth:api'], function(){
        /*User's Profile Routes */
        Route::get('user','User\UserController@index');//getting all the users
        Route::get('user/profile','User\UserController@profile');//getting the user's profile 
        Route::post('user','User\UserController@update');//updating the user's profile
        Route::delete('user','User\UserController@destroy');//deleting the user

        /*favourite schools Routes*/
        Route::post('user/favorites', 'User\UserController@getFavorites');
        Route::post('user/favorites/{school_id}/add', 'User\UserController@AddFavorites');
        Route::post('user/favorites/{school_id}/remove', 'User\UserController@RemoveFavorites');

        //logout
        Route::get('logout', 'AuthController@logout'); 
    });

    /*Suggestions Routes*/
    Route::group(['middleware' => ['auth:api','admin'] ], function(){
        Route::get('suggestions/','SuggestionsController@getNewSchoolSuggestions');
        Route::put('suggestions/{id}','SuggestionsController@approveSuggestion');
    });

});
