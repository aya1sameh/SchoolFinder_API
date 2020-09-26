<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    $user = new User();
    $user->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
    $user->email = $faker->unique()->safeEmail;
    $user->name =$faker->name;
    $user->email_verified_at =now();
    $user->role =  $faker->randomElement(['app_admin','school_admin','school_finder_client']);
    $user->save();
    $tokenResult = $user->createToken('school finder app');
    $token = $tokenResult->token;
    $token->expires_at = Carbon::now()->addDays(365);
    $token->save();
    //$user->access_token = $tokenResult->accessToken;
    $user->remember_token = \bin2hex(openssl_random_pseudo_bytes(30));
    $user->save();
    return [
        'name' => $faker->name, 
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //'remember_token' => Str::random(10),
        'remember_token' => \bin2hex(openssl_random_pseudo_bytes(30)), 
        //'access_token' => $user->access_token,
        'role' => $faker->randomElement(['app_admin','school_admin','school_finder_client']),
        //'favorites' => json_encode(array('1','2'), JSON_NUMERIC_CHECK)
    ];
});
