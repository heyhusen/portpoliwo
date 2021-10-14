<?php

namespace Database\Factories\Blog;

use App\Models\Blog\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence(10, true);
        return [
            'title' => $title,
            'slug' => Str::slug($title, '-'),
            'summary' => $this->faker->paragraph(3, true),
            'content' => $this->faker->text(500),
        ];
    }
}
