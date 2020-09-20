<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(CommentOnPost::class, function (Faker $faker) {
    return [
        'content' => $faker->content, 
         
    ];
});
