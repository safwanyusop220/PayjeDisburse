<?php

namespace Database\Factories;

use App\Models\Receiver;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReceiverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'program_id' => $this->faker->numberBetween(1, 20),
            'status' => $this->faker->randomElement([1, 2, 3, 4]),
            'name' => $this->faker->name,
            'identification_number' => $this->faker->numerify('###########'),
            'address' => $this->faker->address,
            'phone_number' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'bank' => $this->faker->numberBetween(1, 5),
            'account_number' => $this->faker->numerify('###########'),
        ];
    }
}
