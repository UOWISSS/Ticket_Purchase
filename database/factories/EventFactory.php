<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(fake()->numberBetween(1, 5)),
            'event_date_at' => fake()->dateTime(),
            'sale_start_at' => fake()->dateTime(),
            'sale_end_at' => fake()->dateTime(),
            'is_dynamic_price' => fake()->boolean(),
            'max_number_allowed' => fake()->numberBetween(1, 5),
            'image' => 'https://source.unsplash.com/random/640x480?event', // internet "segítsége"
        ];
    }
}
