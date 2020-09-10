<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\SchoolImage;
use Faker\Generator as Faker;

$factory->define(SchoolImage::class, function (Faker $faker) {
    return [
        'school_id'=>factory(\App\Models\School::class),
        //'url'=> fake.bothify(text='public/imgs/schools/?????/#'),
        'url'=>'public/imgs/schools/default'
    ];
});
