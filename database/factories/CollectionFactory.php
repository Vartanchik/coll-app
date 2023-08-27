<?php

namespace Database\Factories;

use App\Models\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Collection>
 */
class CollectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->words(rand(1, 5), true),
            'description' => $this->faker->sentence(rand(5, 10)),
            'target_amount' => $this->faker->randomFloat(2,10000, 100000),
            'link' => $this->faker->url(),
            'created_at' => $this->faker->dateTime()
        ];
    }
}
