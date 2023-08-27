<?php

namespace Database\Factories;

use App\Models\Collection;
use App\Models\Contributor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contributor>
 */
class ContributorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'collection_id' => Collection::factory(),
            'user_name' => $this->faker->name(),
            'amount' => $this->faker->randomFloat(2,1, 1000)
        ];
    }
}
