<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Event;
use App\Models\User;
use App\Models\Seat;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'barcode' => $this->fake()->unique()->numerify('#########'),
            'admission_time' => null,
            'user_id' => User::factory(),
            'event_id' => Event::factory(),
            'seat_id' => Seat::factory(),
            'price' => $this->fake()->randomFloat(2, 3500, 17500),
        ];
    }
}
