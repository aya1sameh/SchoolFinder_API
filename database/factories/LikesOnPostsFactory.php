<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\LikeOnPost;
use Faker\Generator as Faker;

$factory->define(LikeOnPost::class, function (Faker $faker) {
    return [
         'user_id'=>factory(\App\Models\User::class),
        'post_id'=>factory(\App\Models\Post::class),
});
