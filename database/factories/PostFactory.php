<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CommunityPost;
use Faker\Generator as Faker;

$factory->define(CommunityPost::class, function (Faker $faker) {
    return [
        'user_id'=>$faker->numberBetween(1,20),
        'school_id'=>$faker->numberBetween(1,10),
        'CommunityPost_Content'=>$faker->paragraph,
    ];
});
