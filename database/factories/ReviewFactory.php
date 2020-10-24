<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Review;
use Faker\Generator as Faker;

$factory->define(Review::class, function (Faker $faker) {
    return [
        'Review_description'=>$faker->paragraph,
        'rating'=>$faker->randomElement([1,2,3,4,5,6,7,8,9,10]),
        'user_id'=>$faker->numberBetween(1,20),
        'school_id'=>$faker->numberBetween(1,10),
        'num_of_likes'=>$faker->randomNumber,
        'num_of_dislikes'=>$faker->randomNumber,

    ];
});
