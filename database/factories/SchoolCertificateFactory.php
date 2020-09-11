<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\SchoolCertificate;
use Faker\Generator as Faker;

$factory->define(SchoolCertificate::class, function (Faker $faker) {
    return [
        'school_id'=>factory(\App\Models\School::class),
        'certificate'=>$faker->randomElement(['National','IGCSE','SAT','IB']),
    ];
});
