<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Portfolio\Tag;
use Faker\Generator as Faker;

$factory->define(Tag::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'slug' => $faker->domainWord
    ];
});
