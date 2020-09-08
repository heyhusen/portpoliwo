<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Blog\Page;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Page::class, function (Faker $faker) {
    $title = $faker->sentence(10, true);
    return [
        'title' => $title,
        'slug' => Str::slug($title, '-'),
        'content' => $faker->text(500),
    ];
});
