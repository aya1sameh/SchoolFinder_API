<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\SchoolFacility;
use Faker\Generator as Faker;

$factory->define(SchoolFacility::class, function (Faker $faker) {
    return [
        'school_id'=>factory(\App\Models\School::class),
        'type'=>$faker->sentence,
        'description'=>$faker->paragraph,
        'number'=>$faker->randomNumber
    ];
});
