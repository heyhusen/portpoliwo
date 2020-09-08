<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Blog\Post;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Post::class, function (Faker $faker) {
    $title = $faker->sentence(10, true);
    return [
        'title' => $title,
        'slug' => Str::slug($title, '-'),
        'summary' => $faker->paragraph(3, true),
        'content' => $faker->text(500),
    ];
});
