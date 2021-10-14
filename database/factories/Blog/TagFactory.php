<?php

namespace Database\Factories\Blog;

use App\Models\Blog\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TagFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tag::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence(2, true);
        return [
            'title' => $title,
            'slug' => Str::slug($title, '-'),
            'description' => $this->faker->sentence(15, true)
        ];
    }
}
