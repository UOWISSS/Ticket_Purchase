<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Seat;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TicketPurchaseController extends Controller
{
    use AuthorizesRequests;

    protected $pricingService;

    public function __construct(\App\Services\DynamicPricingService $pricingService)
    {
        $this->pricingService = $pricingService;
    }

    public function purchase(Event $event)
    {
        $seatData = Seat::orderBy('seat_number')->get()->map(function($seat) use ($event) {
            return (object)[
                'seat_number' => $seat->seat_number,
                'price' => $this->pricingService->calculateTicketPrice($event, $seat),
            ];
        });

        $booked = Ticket::where('event_id', $event->id)
            ->with('seat')
            ->get()
            ->pluck('seat.seat_number')
            ->toArray();

        $seatsCount = Seat::count();

        return view('ticket.purchase', [
            'event' => $event->load('tickets'),
            'seatData' => $seatData,
            'booked' => $booked,
            'seatsCount' => $seatsCount,
        ]);
    }

    public function purchasePost(Request $request, Event $event)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'seats' => 'required|array|min:1'
        ]);

        $seats = $request->input('seats', []);
        $user = Auth::user();

        $currentTicketCount = Ticket::where('event_id', $event->id)->count();
        $requestedTickets = count($seats);
        $totalSeats = Seat::count();

        if ($currentTicketCount + $requestedTickets > $totalSeats) {
            return back()->withErrors(['error' => 'Sajnos nincs már elég szabad hely az eseményen.']);
        }

        $successCount = 0;
        foreach($seats as $seat_number){
            $seat = Seat::where('seat_number', $seat_number)->first();
            if (!$seat) continue;

            $exists = Ticket::where('event_id', $event->id)
                          ->where('seat_id', $seat->id)
                          ->exists();
            if($exists) continue;


            $barcode = str_pad(rand(0, 999999999), 9, '0', STR_PAD_LEFT);

            Ticket::create([
                'barcode' => $barcode,
                'admission_time' => now(),
                'user_id' => $user->id,
                'event_id' => $event->id,
                'seat_id' => $seat->id,
                'price' => $this->pricingService->calculateTicketPrice($event, $seat),
            ]);
            $successCount++;
        }

        if ($successCount === 0) {
            return back()->withErrors(['error' => 'Nem sikerült jegyet foglalni. A kiválasztott helyek már foglaltak.']);
        }


        $event->load('tickets');
        return redirect()->route('tickets.my')
            ->with('success', "Sikeresen megvásároltál {$successCount} jegyet!");

    }

    public function myTickets()
    {
        $user = Auth::user();

        $tickets = Ticket::where('user_id', $user->id)
            ->with('event', 'seat')
            ->orderBy('admission_time', 'desc')
            ->get();

        $ticketsByEvent = $tickets->groupBy(function($ticket) {
            return $ticket->event_id;
        })->map(function($groupedTickets) {
            return [
                'event' => $groupedTickets->first()->event,
                'tickets' => $groupedTickets->sortBy('seat_id')->values(),
            ];
        })->sortBy(function($group) {
            return $group['event']->event_date_at;
        })->values();



        return view('ticket.my-tickets', [
            'ticketsByEvent' => $ticketsByEvent,
        ]);
    }
}
