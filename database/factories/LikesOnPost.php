<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\LikeOnPost;
use Faker\Generator as Faker;

$factory->define(LikeOnPost::class, function (Faker $faker) {
    return [
        'liked'=> $faker->liked,
        'user_id'=> $faker->user_id,
        'post_id'=> $faker ->post_id
    ];
});
