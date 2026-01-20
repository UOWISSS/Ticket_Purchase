<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Seat;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalEvents = Event::count();
        $totalTickets = Ticket::count();
        $totalRevenue = Ticket::sum('price');

        $topSeats = DB::table('tickets')
            ->select('seat_id', DB::raw('count(*) as sold'))
            ->groupBy('seat_id')
            ->orderByDesc('sold')
            ->limit(3)
            ->get()
            ->map(function($row){
                return [
                    'seat_number' => $row->seat_id,
                    'sold' => (int) $row->sold,
                ];
            });

        $seatsCount = Seat::count();

        $events = Event::withCount('tickets')
            ->withSum('tickets', 'price')
            ->orderBy('event_date_at')
            ->paginate(5);

        return view('admin.dashboard', compact(
            'totalEvents', 'totalTickets', 'totalRevenue', 'topSeats', 'seatsCount', 'events'
        ));
    }
}
