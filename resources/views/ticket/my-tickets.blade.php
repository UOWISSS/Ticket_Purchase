@extends('ticket.layout')

@section('title', 'Megvásárolt jegyeim')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('events.index') }}">Események</a></li>
            <li class="breadcrumb-item active" aria-current="page">Megvásárolt jegyeim</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-body">
            <h2 class="card-title mb-4">Megvásárolt jegyeim</h2>

            @if($ticketsByEvent->isEmpty())
                <div class="alert alert-info">
                    Még nem vásároltál jegyet. <a href="{{ route('events.index') }}">Válassz egy eseményt!</a>
                </div>
            @else
                @foreach($ticketsByEvent as $group)
                    <div class="card mb-4 border-primary">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">{{ $group['event']->title }}</h5>
                            <small>{{ $group['event']->event_date_at->format('Y. m. d. H:i') }}</small>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($group['tickets'] as $ticket)
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card h-100 border-info">
                                            <div class="card-body d-flex flex-column">
                                                <div class="text-center mb-3">
                                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($ticket->barcode) }}"
                                                         alt="QR Code: {{ $ticket->barcode }}"
                                                         style="max-width: 150px; border: 1px solid #ccc; padding: 5px;">
                                                </div>

                                                <div class="mb-3">
                                                    <h6 class="card-title">Ülőhely: <strong>{{ $ticket->seat->seat_number ?? $ticket->seat_id }}</strong></h6>
                                                    <p class="card-text mb-2">
                                                        <strong>Ár:</strong> {{ number_format($ticket->price, 0, ',', ' ') }} Ft
                                                    </p>
                                                    <p class="card-text mb-2">
                                                        <strong>Vonalkód:</strong><br>
                                                        <code style="font-family: 'Courier New', monospace; font-size: 12px;">{{ $ticket->barcode }}</code>
                                                    </p>
                                                </div>

                                                <div class="text-center mt-auto">
                                                    <div style="font-family: 'libre barcode 128', 'code128', monospace; font-size: 32px; letter-spacing: 2px; line-height: 1;">
                                                        {{ $ticket->barcode }}
                                                    </div>
                                                    <small class="text-muted">{{ $ticket->barcode }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="mt-4">
                    <a href="{{ route('events.index') }}" class="btn btn-secondary">Vissza az eseményekhez</a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400;1,700&display=swap');

    @font-face {
        font-family: 'libre barcode 128';
        src: url('https://cdn.jsdelivr.net/npm/librebarcode@0.3.0/dist/fonts/LibreBarcode128-Regular.ttf') format('truetype');
    }

    .card {
        transition: box-shadow 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    #qr-code {
        display: inline-block;
    }
</style>
@endsection
