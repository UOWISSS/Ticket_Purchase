@extends('ticket.layout')

@section('title', 'Jegyvásárlás - ' . $event->title)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('events.index') }}">Események</a></li>
            <li class="breadcrumb-item"><a href="{{ route('events.show', $event) }}">{{ $event->title }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Jegyvásárlás</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-body">
            <h2 class="card-title">{{ $event->title }}</h2>
            <p class="text-muted">{{ $event->event_date_at->format('Y. m. d. H:i') }}</p>

            @if($event->image)
                <img src="{{ $event->image }}" alt="{{ $event->title }}" class="img-fluid mb-3">
            @endif

            <p>{{ $event->description }}</p>

            <hr>

            @php
                // Már eladott jegyek száma
                $soldTicket = \App\Models\Ticket::where('event_id', $event->id)->count();
                // Szabad székek
                $availableSeats = $seatsCount - $soldTicket;
                // felhasználó jegyei
                $purchasedByUser = auth()->check() ? \App\Models\Ticket::where('event_id', $event->id)->where('user_id', auth()->id())->count() : 0;
                //a felhasznalo hanyat vehet még
                $allowedRemaining = max(0, $event->max_number_allowed - $purchasedByUser);

            @endphp

            <p><strong>Szabad helyek:</strong> {{ $availableSeats }} / {{ $seatsCount }}</p>
            <p><strong>Te még vásárolhatsz:</strong> {{ $allowedRemaining }} jegyet</p>


            @guest
                <div class="alert alert-info">A jegyvásárláshoz jelentkezz be vagy regisztrálj.</div>
                <div class="d-flex gap-2">
                    <a href="{{ route('login') }}" class="btn btn-primary">Bejelentkezés</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary">Regisztráció</a>
                </div>
            @else
                @if($availableSeats <= 0)
                    <div class="alert alert-danger">Sajnáljuk, minden jegy elkelt.</div>
                @else
                    @if(session('errors'))
                        <div class="alert alert-danger">{{ session('errors')->first() }}</div>
                    @endif
                    <form action="{{ route('events.purchase.post', $event) }}" method="POST" id="purchase-form">
                        @csrf

                        <div class="mb-3">
                            <small class="text-muted">Kattints az ülőhelyre a kiválasztáshoz. A kiválasztott helyek kék színnel jelölve lesznek.</small>
                        </div>

                        <style>
                            .seat-item.selected { background: #0d6efd; color: #fff; }
                            .seat-item.booked { background: #dc3545; color: #fff; }
                        </style>

                        <div class="row g-2 mb-3" id="seats-grid">
                            @foreach($seatData as $seat)
                                @php
                                    $isBooked = in_array($seat->seat_number, $booked);
                                    $price = number_format($seat->price, 2);
                                @endphp
                                <div class="col-2">
                                    <label class="d-block text-center p-2 border rounded seat-item @if($isBooked) booked @else free @endif" data-seat="{{ $seat->seat_number }}" style="cursor: pointer;">
                                        <input type="checkbox" name="seats[]" value="{{ $seat->seat_number }}" class="d-none seat-checkbox" @if($isBooked) disabled @endif>
                                        <div class="fw-bold">{{ $seat->seat_number }}</div>
                                        <div class="small text-muted">{{ $price }} Ft</div>
                                        @if($isBooked)
                                            <div class="badge bg-danger mt-1">Foglalt</div>
                                        @endif
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-success" id="buy-btn">Jegyek vásárlása</button>
                        </div>
                    </form>

                    <script>
                        (function(){
                            const allowed = {{ $allowedRemaining }};
                            const seatItems = document.querySelectorAll('.seat-item');
                            const form = document.getElementById('purchase-form');
                            const buyBtn = document.getElementById('buy-btn');

                            function updateSelectionCount(){
                                const checked = document.querySelectorAll('.seat-checkbox:checked').length;
                                if(allowed === 0){
                                    buyBtn.disabled = true;
                                    return;
                                }
                                buyBtn.disabled = checked === 0;
                                if(checked > allowed){
                                    buyBtn.disabled = true;
                                }
                            }

                            seatItems.forEach(item => {
                                if(item.classList.contains('booked')) return;
                                item.addEventListener('click', function(e){
                                    const cb = item.querySelector('.seat-checkbox');
                                    cb.checked = !cb.checked;
                                    item.classList.toggle('selected', cb.checked);
                                    updateSelectionCount();
                                });
                            });

                            form.addEventListener('submit', function(e){
                                const checked = document.querySelectorAll('.seat-checkbox:checked').length;
                                if(checked === 0){
                                    e.preventDefault();
                                    alert('Legalább egy szabad helyet ki kell választani.');
                                    return;
                                }
                                if(checked > allowed){
                                    e.preventDefault();
                                    alert('A kiválasztott helyek száma meghaladja a megvásárolható mennyiséget: ' + allowed);
                                    return;
                                }
                            });

                            // init
                            updateSelectionCount();
                        })();
                    </script>
                    <script>window.addEventListener('pageshow', e => e.persisted && location.reload());</script>
                @endif
            @endguest
        </div>
    </div>
</div>
@endsection
