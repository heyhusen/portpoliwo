<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\SocialMedia;
use Faker\Generator as Faker;

$factory->define(SocialMedia::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'icon' => $faker->slug,
        'url' => $faker->domainName
    ];
});
