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
});

//Auth::routes(['verify' => true]);

//Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');
//for forgetting the password page (to be changed in the front end)
//Route::view('/reset_password', 'auth.reset_password')->name('password.reset');

Route::get('/reset_password', function () {
    $request = request();
    $email= $request->email;
    $token= $request->token;
    $url = 'http://192.168.1.12:8081/reset_password?token='.$token.'&email='.$email;
    return redirect($url,302,['email'=>$email,'token'=>$token]);
})->name('password.reset');

//here i changed the login page that is redirected by the auth package.. 
/*Route::get('/login', function () {
    return view('welcome');
})->name('login');*/


