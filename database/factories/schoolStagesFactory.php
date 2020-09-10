<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\SchoolStage;
use Faker\Generator as Faker;

$factory->define(SchoolStage::class, function (Faker $faker) {
    return [
        'school_id'=>factory(\App\Models\School::class),
        'stage'=>$faker->randomElement(['nursery','KG','Primary','Secondary']),
    ];
});
