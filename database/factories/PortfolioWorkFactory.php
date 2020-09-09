<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Portfolio\Work;
use Faker\Generator as Faker;

$factory->define(Work::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(5, true),
        'description' => $faker->text(300),
        'url' => $faker->domainName
    ];
});
