<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\School;
use Faker\Generator as Faker;

$factory->define(School::class, function (Faker $faker) {
    return [
        'name'=>$faker->name,
        'school_stages'=>$faker->randomElement(['nursery','KG','Primary','Secondary']),
        'school_certificate'=>$faker->randomElement(['National','IGCSE','SAT','IB']),
        'gender'=>$faker->randomElement(['Mix','Girls Only','Boys Only']),
        'language'=>$faker->sentence,
        'address' => $faker->address,
        'phone_number'=> $faker->phoneNumber,
        'fees'=>$faker->randomNumber,
        'description'=>$faker->paragraph,
        'is_approved'=>$faker->boolean,
        'establishing_year'->$faker->year,
    ];
    
});
