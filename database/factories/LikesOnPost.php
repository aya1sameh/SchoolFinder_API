<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(LikeOfPost::class, function (Faker $faker) {
    return [
        'liked'=>$faker->boolean
    ];
});
