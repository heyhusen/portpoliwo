<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Blog\Category;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Category::class, function (Faker $faker) {
    $title = $faker->sentence(2, true);
    return [
        'title' => $title,
        'slug' => Str::slug($title, '-'),
        'description' => $faker->sentence(15, true)
    ];
});
