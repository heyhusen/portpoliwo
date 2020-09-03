<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Blog\Category;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'slug' => $faker->domainWord,
        'description' => $faker->sentence($nbWords = 15, $variableNbWords = true)
    ];
});
