<?php

namespace Database\Seeders;

use App\Models\Seat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $A = fake()->numberBetween(78, 100)*100;
        $B = fake()->numberBetween(52, 78)*100;
        $C = fake()->numberBetween(25, 52)*100;
        $D = fake()->numberBetween(10, 25)*100;

        foreach (range('A', 'D') as $section) {
            for ($i = 1; $i <= 25; $i++) {
                $seatNumber = $section . str_pad($i, 3, '0', STR_PAD_LEFT);

                $basePrice = match($section) {
                    'A' => $A,
                    'B' =>  $B,
                    'C' =>  $C,
                    'D' =>  $D,
                };

                Seat::create([
                    'seat_number' => $seatNumber,
                    'base_price' => $basePrice,
                ]);
            }
        }


    }
}
