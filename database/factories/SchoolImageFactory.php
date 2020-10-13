<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\SchoolImage;
use Faker\Generator as Faker;

$factory->define(SchoolImage::class, function (Faker $faker) {
    return [
        'school_id'=>factory(\App\Models\School::class),
        'url'=>$faker->randomElement(['/imgs/schools/school1.jpe','/imgs/schools/school2.jpe','/imgs/schools/school3.jpe','/imgs/schools/school4.jpe','/imgs/schools/school5.jpe']),
    ];
});
