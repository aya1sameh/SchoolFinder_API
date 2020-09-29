<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\LikesOfReview;
use Faker\Generator as Faker;

$factory->define(LikesOfReview::class, function (Faker $faker) {
    return [
        'user_id'=>factory(\App\Models\User::class),
        'review_id'=>factory(\App\Models\Review::class),
    
    ];
});
