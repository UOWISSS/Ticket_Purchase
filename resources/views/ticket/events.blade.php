@extends('ticket.layout')

@section('title', 'Események')
@section('content')
    <h1 class="ps-3">Események</h1>
    <hr />
    <div class="table-responsive">
        <table class="table align-middle table-hover">
            <thead class="text-center table-light">
                <tr>
                    <th style="width: 10%">Állapot</th>
                    <th style="width: 15%">Esemény</th>
                    <th style="width: 15%">Esemény időpontja</th>
                    <th style="width: 15%">Részletek</th>
                    <th style="width: 25%">Borítókép</th>
                    <th style="width: 20%">Jegyek</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($events as $event)
                    @php 
                        $now = now();
                        $availableSeats = $seatsCount - $event->tickets_count;
                        $status = match(true) {
                            $now > $event->event_date_at => ['text' => 'Lezárult', 'class' => 'bg-secondary'],
                            $now < $event->sale_start_at => ['text' => 'Közelgő', 'class' => 'bg-info'],
                            $now > $event->sale_end_at => ['text' => 'Jegyárusítás vége', 'class' => 'bg-danger'],
                            $availableSeats <= 0 => ['text' => 'Teltház', 'class' => 'bg-danger'],
                            $now >= $event->sale_start_at && $now <= $event->sale_end_at => ['text' => 'Jegyárusítás', 'class' => 'bg-success'],
                            default => ['text' => 'Ismeretlen', 'class' => 'bg-secondary']
                        };
                    @endphp
                    <tr>
                        <td>
                            <span class="badge rounded-pill {{ $status['class'] }} fs-6">{{ $status['text'] }}</span>
                        </td>
                        <td class="text-start">
                            <div class="fw-bold">{{ $event->title }}</div>
                            <div class="text-secondary small">{{ \Illuminate\Support\Str::limit($event->description, 80) }}</div>
                        </td>
                        <td>
                            <div>{{ $event->event_date_at->format('Y. m. d. H:i') }}</div>
                            <div class="text-secondary small">Esemény kezdete</div>
                        </td>
                        <td>
                            <div>
                                <a href="{{ route('events.show', $event) }}">Részletek</a>
                            </div>
                        </td>
                        <td>
                            @if ($event->image)
                                <img src="{{ $event->image }}" alt="{{ $event->title }}" style="max-width:80px; height:auto;">
                            @else
                                <div class="bg-secondary text-white p-2">Nincs kép</div>
                            @endif
                        </td>
                        <td>
                            @php
                                $percentage = ($availableSeats / $seatsCount) * 100;
                                $progressClass = match (true) {
                                    $percentage < 20 => 'bg-danger',
                                    $percentage < 50 => 'bg-warning',
                                    default => 'bg-success',
                                };
                            @endphp
                            <div class="mb-2">{{ $availableSeats }} szabad hely</div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar {{ $progressClass }}" role="progressbar"
                                    style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-ticket-alt me-1"></i>Jegyvásárlás
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    {{-- 
    <div class="d-flex justify-content-center mt-4">
        {{ $events->links() }}
    </div>
     --}}


    <div class="d-flex justify-content-center mt-4">
        <nav aria-label="Lapozás">
            <ul class="pagination pagination-sm mb-0 shadow-sm" style="border-radius: 8px;">
                {{-- Előző oldal gomb --}}
                @if ($events->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link border-0 text-muted bg-light">«</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link border-0 text-dark bg-light" href="{{ $events->previousPageUrl() }}"
                            rel="prev">«</a>
                    </li>
                @endif

                {{-- Oldalszámok --}}
                @foreach ($events->getUrlRange(1, $events->lastPage()) as $page => $url)
                    <li class="page-item {{ $page == $events->currentPage() ? 'active' : '' }}">
                        <a class="page-link border-0 {{ $page == $events->currentPage() ? 'bg-primary text-white' : 'text-dark bg-light' }}"
                            href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach

                {{-- Következő oldal gomb --}}
                @if ($events->hasMorePages())
                    <li class="page-item">
                        <a class="page-link border-0 text-dark bg-light" href="{{ $events->nextPageUrl() }}"
                            rel="next">»</a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link border-0 text-muted bg-light">»</span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>


@endsection
