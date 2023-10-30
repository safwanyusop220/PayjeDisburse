<?php

namespace Database\Factories;

use App\Models\Allocation;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class AllocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'allocation_source' => $this->faker->word,
            'total_allocation' => $this->faker->randomFloat(2, 1000, 1000000),
            'allocation_balance' => $this->faker->randomFloat(2, 0, 1000000),
            'status' => $this->faker->randomElement([1, 2, 3]),
        ];
    }
}
