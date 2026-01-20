<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventAdminController extends Controller
{
    public function create()
    {
        return view('admin.event-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date_at' => 'required|date',
            'sale_start_at' => 'required|date',
            'sale_end_at' => 'required|date|after_or_equal:sale_start_at',
            'max_number_allowed' => 'required|integer|min:1',
            'image' => 'nullable|url',
        ]);
        $validated['is_dynamic_price'] = $request->boolean('is_dynamic_price', false);

        $event = Event::create($validated);
        return redirect()->route('dashboard')->with('success', 'Esemény sikeresen létrehozva!');
    }

    public function edit(Event $event)
    {
        $salesHaveStarted = now() >= $event->sale_start_at;
        return view('admin.event-edit', compact('event', 'salesHaveStarted'));
    }

    public function update(Request $request, Event $event)
    {
        $salesHaveStarted = now() >= $event->sale_start_at;

        if ($salesHaveStarted) {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|url',
            ]);
        } else {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'event_date_at' => 'required|date',
                'sale_start_at' => 'required|date',
                'sale_end_at' => 'required|date|after_or_equal:sale_start_at',
                'max_number_allowed' => 'required|integer|min:1',
                'image' => 'nullable|url',
                'is_dynamic_price' => 'nullable|boolean',
            ]);
            if (isset($validated['is_dynamic_price'])) {
                $validated['is_dynamic_price'] = $request->boolean('is_dynamic_price', false);
            }
        }

        $event->update($validated);
        return redirect()->route('dashboard')->with('success', 'Esemény sikeresen módosítva!');
    }

    public function destroy(Event $event)
    {
        if ($event->tickets()->exists()) {
            return redirect()->route('dashboard')->with('error', 'Az esemény nem törölhető, mert már van rá eladott jegy!');
        }

        $event->delete();
        return redirect()->route('dashboard')->with('success', 'Esemény sikeresen törölve!');
    }
}
