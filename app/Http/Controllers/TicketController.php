<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Seat;

class TicketController extends Controller
{
    public function index()
    {
        $events = Event::withCount('tickets')
            //->where('event_date_at', '>', now())
            ->orderBy('event_date_at', 'asc')
            ->paginate(5);

        $seatsCount = Seat::count();

        return view('ticket.events', [
            'events' => $events,
            'seatsCount' => $seatsCount
        ]);
    }

    public function show(Event $event)
    {
        return view('ticket.event-details', [
            'event' => $event->load('tickets'),
            'seatsCount' => Seat::count()
        ]);
    }
}
