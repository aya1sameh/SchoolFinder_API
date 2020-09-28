<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CommunityPost;
use Faker\Generator as Faker;

$factory->define(CommunityPost::class, function (Faker $faker) {
    return [
        'user_id'=>factory(\App\Models\User::class),
        'school_id'=>factory(\App\Models\School::class),
        'CommunityPost_Content'=>$faker->paragraph,
    ];
});
