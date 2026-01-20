<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketAdminController extends Controller
{
    public function scan()
    {
        return view('admin.ticket-scan');
    }

    public function processScan(Request $request)
    {
        $validated = $request->validate([
            'barcode' => 'required|string',
        ]);

        $barcode = $validated['barcode'];

        $ticket = Ticket::where('barcode', $barcode)->first();

        if (!$ticket) {
            return back()->with('error', 'A jegy nem található az adatbázisban!');
        }

        if ($ticket->admission_time != null) {
            return back()->with('error', "A jegy már be volt olvasva: {$ticket->admission_time->format('Y-m-d H:i:s')}")->withInput();
        }

        $scannedAt = now();
        $ticket->update(['admission_time' => $scannedAt]);

        return back()->with('success', "Jegy sikeresen beolvasva! Beolvasási idő: {$scannedAt->format('Y-m-d H:i:s')}");
    }
}
