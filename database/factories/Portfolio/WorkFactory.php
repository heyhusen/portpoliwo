<?php

namespace Database\Factories\Portfolio;

use App\Models\Portfolio\Work;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Work::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(5, true),
            'description' => $this->faker->text(300),
            'url' => $this->faker->domainName
        ];
    }
}
