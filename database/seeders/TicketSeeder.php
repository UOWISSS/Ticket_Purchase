<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Seat;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $events = Event::all();
        $seats = Seat::all();
        $users = User::all();

        foreach ($events as $event) {
            $randomUsers = $users->random(fake()->numberBetween(3, 5));

            foreach ($randomUsers as $user) {
                $randomSeats = $seats->random(fake()->numberBetween(1, 3));

                foreach ($randomSeats as $seat) {
                    $admissionTime = Carbon::parse($event->sale_start_at)
                        ->addSeconds(rand(0, Carbon::parse($event->event_date_at)->diffInSeconds($event->sale_start_at)));

                    $price = $event->is_dynamic_price
                        ? $this->calculateDynamicPrice($seat->base_price, $event, $admissionTime)
                        : $seat->base_price;

                    Ticket::create([
                        'barcode' => fake()->numerify('#########'), // 9-digit number
                        'user_id' => $user->id,
                        'event_id' => $event->id,
                        'seat_id' => $seat->id,
                        'price' => $price,
                        'admission_time' => null,
                    ]);
                }
            }
        }

    }

    /**
     * Calculate dynamic price
     */
    private function calculateDynamicPrice(int $basePrice, Event $event, Carbon $purchaseTime): int
    {
        $dinamicPrice = 0;

        $daysUntil = now()->diffInDays($event->sale_start_at);
        $occ = Ticket::where('event_id', $event->id)->count()/Seat::count();

        $dinamicPrice = $basePrice* (1- 0.5 * (1/($daysUntil+1)))*(1+0.5*$occ);


        return $dinamicPrice;
    }

}
