<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Seat;
use Carbon\Carbon;

class DynamicPricingService
{    public function calculateTicketPrice(Event $event, Seat $seat): float
    {
        $basePrice = $seat->base_price;

        if (!$event->is_dynamic_price) {
            return $basePrice;
        }

        // Az eseményig hátralévő idő alapján számoljuk az árat
        $daysUntilEvent = Carbon::now()->diffInDays($event->event_date_at, false);

        // Az eladott jegyek száma alapján is módosítjuk az árat
        $soldTicketsCount = $event->tickets()->count();
        $maxTickets = $event->max_number_allowed;
        $soldPercentage = ($soldTicketsCount / $maxTickets) * 100;

        // Foglaltsági ráta (0-1 között)
        $occupancy = $soldTicketsCount / $maxTickets;

        // A képlet implementálása:
        // Price = BasePrice × (1 - 0.5 × (1 / (DaysUntil + 1))) × (1 + 0.5 × Occupancy)
        $timeMultiplier = 1 - 0.5 * (1 / ($daysUntilEvent + 1));
        $occupancyMultiplier = 1 + 0.5 * $occupancy;

        $finalPrice = $basePrice * $timeMultiplier * $occupancyMultiplier;

        return round($finalPrice);
    }
}
