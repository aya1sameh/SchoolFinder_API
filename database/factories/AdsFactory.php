<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Ads;
use Faker\Generator as Faker;

$factory->define(Ads::class, function (Faker $faker) {
    return [
            'user_id'=>$faker->numberBetween(21,25),
            'ad_content'=>'AD Content',
            'ad_image_url'=>$faker->randomElement(['/imgs/ads/ad1.jpe','/imgs/ads/ad2.jpe','/imgs/ads/ad3.jpe','/imgs/ads/ad4.jpe','/imgs/ads/ad5.jpe']),
        ];
    
});
