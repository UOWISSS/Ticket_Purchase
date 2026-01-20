@extends('ticket.layout')

@section('title', 'Irányítópult')

@section('content')
<div class="container py-4">
    <h1>Irányítópult (Admin)</h1>

    <div class="row g-3 my-3">
        <div class="col-md-3">
            <div class="card p-3">
                <h5>Összes esemény</h5>
                <div class="display-6">{{ $totalEvents }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3">
                <h5>Eladott jegyek (db)</h5>
                <div class="display-6">{{ $totalTickets }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3">
                <h5>Összbevétel (Ft)</h5>
                <div class="display-6">{{ number_format($totalRevenue, 2) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3">
                <h5>Összes szék</h5>
                <div class="display-6">{{ $seatsCount }}</div>
            </div>
        </div>
    </div>

    <h3>Top 3 legnépszerűbb ülőhely</h3>
    <div class="row mb-4">
        @foreach($topSeats as $seat)
            <div class="col-md-4">
                <div class="card p-3">
                    <h6>Ülőhely: {{ $seat['seat_number'] }}</h6>
                    <div>Eladott jegyek: {{ $seat['sold'] }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <h3>Kezelési felületek</h3>
    <div class="list-group mb-4">
        <a href="{{ route('admin.seats.index') }}" class="list-group-item list-group-item-action">
            <i class="fas fa-chair me-2"></i>Ülőhelyek kezelése
        </a>
        <a href="{{ route('admin.tickets.scan') }}" class="list-group-item list-group-item-action ps-5">
            <i class="fas fa-barcode me-2"></i>Jegykezelés - Vonalkód beolvasás
        </a>
    </div>

    <h3>Események</h3>
    <div class="list-group mb-3">
        @foreach($events as $event)
            <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5>{{ $event->title }}</h5>
                        <div class="text-muted">{{ $event->event_date_at->format('Y. m. d. H:i') }}</div>
                    </div>
                    <div class="text-end">
                        @php
                            $free = $seatsCount - $event->tickets_count;
                            $revenue = $event->tickets_sum_price ?? 0;
                            $canDelete = $event->tickets_count == 0;
                        @endphp
                        <div><strong>Szabad jegyek:</strong> {{ $free }}</div>
                        <div><strong>Bevétel:</strong> {{ number_format($revenue, 2) }} Ft</div>
                        <div class="mt-2">
                            <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit me-1"></i>Szerkesztés
                            </a>
                            @if($canDelete)
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $event->id }}">
                                    <i class="fas fa-trash me-1"></i>Törlés
                                </button>
                            @else
                                <button type="button" class="btn btn-sm btn-danger" disabled title="Az esemény nem törölhető, mert már van rá eladott jegy!">
                                    <i class="fas fa-trash me-1"></i>Törlés
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($canDelete)
                <div class="modal fade" id="deleteModal{{ $event->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $event->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel{{ $event->id }}">Esemény törlése</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Biztos, hogy törölni szeretnéd a <strong>{{ $event->title }}</strong> eseményt?</p>
                                <p class="text-muted">Ez a művelet nem vonható vissza!</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégsem</button>
                                <form method="POST" action="{{ route('admin.events.destroy', $event) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Törlés</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4">
        <nav aria-label="Lapozás">
            <ul class="pagination pagination-sm mb-0 shadow-sm" style="border-radius: 8px;">
                @if ($events->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link border-0 text-muted bg-light">«</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link border-0 text-dark bg-light" href="{{ $events->previousPageUrl() }}" rel="prev">«</a>
                    </li>
                @endif

                @foreach ($events->getUrlRange(1, $events->lastPage()) as $page => $url)
                    <li class="page-item {{ $page == $events->currentPage() ? 'active' : '' }}">
                        <a class="page-link border-0 {{ $page == $events->currentPage() ? 'bg-primary text-white' : 'text-dark bg-light' }}" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach

                @if ($events->hasMorePages())
                    <li class="page-item">
                        <a class="page-link border-0 text-dark bg-light" href="{{ $events->nextPageUrl() }}" rel="next">»</a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link border-0 text-muted bg-light">»</span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</div>
@endsection
