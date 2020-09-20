<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\School;
use Faker\Generator as Faker;


$factory->define(School::class, function (Faker $faker) {
    return [
        'name'=>$faker->name,
        'gender'=>$faker->randomElement(['Mix','Girls Only','Boys Only']),
        'language'=>$faker->sentence,
        'address' => $faker->address,
        //'phone_number'=> $faker->phoneNumber,
        'phone_number'=> $faker->randomNumber,
        'fees'=>$faker->randomNumber,
        'description'=>$faker->paragraph,
        'is_approved'=>$faker->boolean,
        'establishing_year'=>$faker->year,
        'admin_id'=>factory(\App\Models\User::class),
    ];
    
});
