<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Review;
use Faker\Generator as Faker;

$factory->define(Review::class, function (Faker $faker) {
    return [
        'Review_description'=>$faker->paragraph,
        'rating'=>$faker->randomElement([1,2,3,4,5,6,7,8,9,10]),
        'user_id'=>factory(\App\Models\User::class),
        'school_id'=>factory(\App\Models\School::class),
    ];
});
