<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Portfolio\Category;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'slug' => $faker->domainWord
    ];
});
