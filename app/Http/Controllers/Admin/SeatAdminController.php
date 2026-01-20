<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seat;
use Illuminate\Http\Request;

class SeatAdminController extends Controller
{
    public function index()
    {
        $seats = Seat::withCount('tickets')->orderBy('seat_number')->paginate(10);
        return view('admin.seats-index', compact('seats'));
    }

    public function create()
    {
        return view('admin.seat-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'seat_number' => 'required|string|max:50|unique:seats,seat_number',
            'base_price' => 'required|integer|min:0',
        ]);

        $validated['seat_number'] = strtoupper($validated['seat_number']);

        Seat::create($validated);
        return redirect()->route('admin.seats.index')->with('success', 'Ülőhely sikeresen létrehozva!');
    }

    public function edit(Seat $seat)
    {
        return view('admin.seat-edit', compact('seat'));
    }

    public function update(Request $request, Seat $seat)
    {
        $validated = $request->validate([
            'seat_number' => 'required|string|max:50|unique:seats,seat_number,' . $seat->seat_number . ',seat_number',
            'base_price' => 'required|integer|min:0',
        ]);

        $validated['seat_number'] = strtoupper($validated['seat_number']);

        $seat->update($validated);
        return redirect()->route('admin.seats.index')->with('success', 'Ülőhely sikeresen módosítva!');
    }

    public function destroy(Seat $seat)
    {
        if ($seat->tickets()->exists()) {
            return redirect()->route('admin.seats.index')->with('error', 'Az ülőhely nem törölhető, mert már van rá eladott jegy!');
        }

        $seat->delete();
        return redirect()->route('admin.seats.index')->with('success', 'Ülőhely sikeresen törölve!');
    }
}
