<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CommentOnPost;
use Faker\Generator as Faker;

$factory->define(CommentOnPost::class, function (Faker $faker) {
    return [
        'content' => $faker->paragraph, 
        'user_id'=>$faker->numberBetween(1,20),
        'post_id'=>$faker->numberBetween(1,50),

    ];
});
