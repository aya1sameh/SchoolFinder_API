<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\LikesOfReview;
use Faker\Generator as Faker;

$factory->define(LikesOfReview::class, function (Faker $faker) {
    return [
        'user_id'=>$faker->numberBetween(1,20),
        'review_id'=>$faker->numberBetween(1,50),
    
    ];
});
