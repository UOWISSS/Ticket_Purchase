<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seat>
 */
class SeatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'seat_number' => strtoupper($this->faker->randomLetter()) . $this->faker->numerify('###'),
            'base_price' => fake()->numberBetween(3500, 15000),
        ];
    }
}
