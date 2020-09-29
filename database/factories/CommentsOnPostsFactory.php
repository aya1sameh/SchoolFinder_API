<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CommentOnPost;
use Faker\Generator as Faker;

$factory->define(CommentOnPost::class, function (Faker $faker) {
    return [
        'content' => $faker->paragraph, 
        'user_id'=>factory(\App\Models\User::class),
        'post_id'=>factory(\App\Models\Post::class),

    ];
});
