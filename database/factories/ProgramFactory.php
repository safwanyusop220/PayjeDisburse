<?php

namespace Database\Factories;

use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProgramFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word,
            'allocation' => $this->faker->numberBetween(1, 4), // Adjust the range as needed
            'type_id' => $this->faker->numberBetween(1, 4), // Adjust the range as needed
            'allocation_rate' => $this->faker->randomFloat(2, 50, 1000), // Adjust the range as needed
            'status' => $this->faker->numberBetween(1, 3), // Adjust the range as needed
        ];
    }
}
