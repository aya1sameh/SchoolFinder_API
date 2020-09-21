<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CommentOnPost;
use Faker\Generator as Faker;

$factory->define(CommentOnPost::class, function (Faker $faker) {
    return [
        'content' => $faker->content, 
        'user_id'=> $faker->user_id,
        'post_id'=> $faker->post_id

    ];
});
