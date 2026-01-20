@extends('ticket.layout')

@section('title', $event->title)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Események</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $event->title }}</li>
        </ol>
    </nav>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="card-title mb-0">{{ $event->title }}</h1>
                        <span class="badge bg-primary fs-5">{{ $event->event_date_at->format('Y. m. d. H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2">Esemény leírása</h5>
                    <p class="card-text">{{ $event->description }}</p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2">Esemény adatok</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Kezdés időpontja:</strong></p>
                            <p class="text-primary">{{ $event->event_date_at->format('Y. m. d. H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Maximum jegy/fő:</strong></p>
                            <p class="text-primary">{{ $event->max_number_allowed }} db</p>
                        </div>
                        <div class="col-12">
                            <p class="mb-1"><strong>Jegyárusítás időszaka:</strong></p>
                            <p class="text-primary">
                                {{ $event->sale_start_at->format('Y. m. d. H:i') }} -
                                {{ $event->sale_end_at->format('Y. m. d. H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if($event->image)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2">Esemény képe</h5>
                    <img src="{{ $event->image }}" alt="{{ $event->title }}" class="img-fluid rounded">
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2">Jegyvásárlás</h5>

                    @php
                        // Eladott jegyek száma az adott eseményhez
                        $soldTicket = \App\Models\Ticket::where('event_id', $event->id)->count();
                        // Szabad székek
                        $availableSeats = $seatsCount - $soldTicket;
                        $percentage = ($availableSeats / $seatsCount) * 100;
                        $now = now();
                        $saleStatus = match(true) {
                            $now < $event->sale_start_at => 'future',
                            $now > $event->sale_end_at => 'ended',
                            $availableSeats <= 0 => 'sold_out',
                            default => 'active'
                        };
                    @endphp

                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Szabad helyek</span>
                            <span class="fw-bold">{{ $availableSeats }} / {{ $seatsCount }}</span>
                        </div>
                        <div class="progress" style="height: 15px;">
                            <div class="progress-bar @if($percentage < 20) bg-danger @elseif($percentage < 50) bg-warning @else bg-success @endif"
                                 role="progressbar"
                                 style="width: {{ $percentage }}%"
                                 aria-valuenow="{{ $percentage }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="alert @if($event->is_dynamic_price) alert-warning @else alert-info @endif">
                            <i class="fas @if($event->is_dynamic_price) fa-chart-line @else fa-tag @endif me-2"></i>
                            @if($event->is_dynamic_price)
                                Dinamikus jegyárazás van érvényben
                            @else
                                Fix jegyárak
                            @endif
                        </div>
                    </div>

                    @switch($saleStatus)
                        @case('active')
                            <a href="{{ route('events.purchase', $event) }}" class="btn btn-primary w-100">
                                <i class="fas fa-ticket-alt me-2"></i>Jegyek vásárlása
                            </a>
                            @break
                        @case('future')
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-clock me-2"></i>
                                A jegyárusítás kezdete:<br>
                                <strong>{{ $event->sale_start_at->format('Y. m. d. H:i') }}</strong>
                            </div>
                            @break
                        @case('ended')
                            <div class="alert alert-secondary mb-0">
                                <i class="fas fa-calendar-times me-2"></i>
                                A jegyárusítás lezárult
                            </div>
                            @break
                        @case('sold_out')
                            <div class="alert alert-danger mb-0">
                                <i class="fas fa-ticket-alt me-2"></i>
                                Minden jegy elkelt!
                            </div>
                            @break
                    @endswitch
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
