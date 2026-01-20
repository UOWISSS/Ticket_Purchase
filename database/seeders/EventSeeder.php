<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentDate = Carbon::now();

        // Event 1: alap
        Event::factory()->create([

            'title' => fake()->unique()->sentence(3),
            'description' => fake()->paragraph(),
            'event_date_at' => $currentDate->copy()->addDays(35),
            'sale_start_at' => $currentDate->copy()->addDays(5),
            'sale_end_at' => $currentDate->copy()->addDays(5)->addDay(fake()->numberBetween(5, 15)),
            'is_dynamic_price' => true,
            'max_number_allowed' => fake()->numberBetween(1,10),
            'image' => 'https://source.unsplash.com/random/640x480?event',

        ]);


        // Event 2: Az árusítás zajik
        Event::create([
            'title' => fake()->unique()->sentence(4),
            'description' => fake()->paragraph(),
            'event_date_at' => $currentDate->copy()->addDays(30),
            'sale_start_at' => $currentDate,
            'sale_end_at' => $currentDate->copy()->addDays(fake()->numberBetween(10, 20)),
            'is_dynamic_price' => true,
            'max_number_allowed' => fake()->numberBetween(1, 10),
            'image' => 'https://source.unsplash.com/random/640x480?event',

        ]);

        // Event 2: Az árusítás zajik
        Event::create([
            'title' => fake()->unique()->sentence(4),
            'description' => fake()->paragraph(),
            'event_date_at' => $currentDate->copy()->addDays(29),
            'sale_start_at' => $currentDate,
            'sale_end_at' => $currentDate->copy()->addDays(fake()->numberBetween(10, 20)),
            'is_dynamic_price' => false,
            'max_number_allowed' => fake()->numberBetween(1, 10),
            'image' => 'https://source.unsplash.com/random/640x480?event',

        ]);


        // Event 3: Már lezajlott a jegyárusítás
        Event::create([
            'title' => fake()->unique()->sentence(5),
            'description' => fake()->paragraph(),
            'event_date_at' => $currentDate->copy()->addDays(4),
            'sale_start_at' => $currentDate->copy()->subDays(10),
            'sale_end_at' => $currentDate->copy()->subDays(fake()->numberBetween(2, 5)),
            'is_dynamic_price' => fake()->boolean(),
            'max_number_allowed' => fake()->numberBetween(1, 10),
            'image' => 'https://source.unsplash.com/random/640x480?event',

        ]);



        Event::factory(2)->create();

        /*
        Event::factory(7)->create([
            'title' => fake()->unique()->sentence(5),
            'description' => fake()->paragraph(),
            'event_date_at' => $currentDate->copy()->addDays(fake()->numberBetween(-60, 180)),
            'sale_start_at' => $currentDate->copy()->addDays(-50,50),
            'sale_end_at' => $currentDate->copy()->subDays(fake()->numberBetween(2, 5)),
            'is_dynamic_price' => fake()->boolean(),
            'max_number_allowed' => fake()->numberBetween(2000, 10000),
        ]);*/


    }
}
