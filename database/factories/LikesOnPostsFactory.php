<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\LikeOnPost;
use Faker\Generator as Faker;

$factory->define(LikeOnPost::class, function (Faker $faker) {
    return [
         'user_id'=>$faker->numberBetween(1,20),
        'post_id'=>$faker->numberBetween(1,50),
    ];
});
