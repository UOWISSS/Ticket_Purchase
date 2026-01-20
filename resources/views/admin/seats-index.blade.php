@extends('ticket.layout')

@section('title', 'Ülőhelyek kezelése')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Ülőhelyek kezelése</h1>
        <a href="{{ route('admin.seats.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-1"></i>Új ülőhely
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Validálási hibák!</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="table-responsive">
        <div class="mb-2 text-muted">Összes ülőhely: <strong>{{ $seats->total() }}</strong></div>
        <table class="table table-hover table-striped table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th class="text-start">Ülőhely</th>
                    <th class="text-end">Alapár (Ft)</th>
                    <th class="text-center">Eladott jegyek</th>
                    <th class="text-center">Műveletek</th>
                </tr>
            </thead>
            <tbody>
                @forelse($seats as $seat)
                    <tr>
                        <td class="text-start">{{ $seat->seat_number }}</td>
                        <td class="text-end">{{ number_format($seat->base_price, 0) }} Ft</td>
                        <td class="text-center">{{ $seat->tickets_count }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.seats.edit', $seat) }}" class="btn btn-sm btn-outline-primary me-1">
                                <i class="fas fa-edit me-1"></i>Szerkesztés
                            </a>
                            @if($seat->tickets_count == 0)
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $seat->id }}">
                                    <i class="fas fa-trash me-1"></i>Törlés
                                </button>
                            @else
                                <button type="button" class="btn btn-sm btn-danger" disabled title="Az ülőhely nem törölhető, mert már van rá eladott jegy!">
                                    <i class="fas fa-trash me-1"></i>Törlés
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            Nincsenek ülőhelyek a rendszerben.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @foreach($seats as $seat)
        @if($seat->tickets_count == 0)
            <div class="modal fade" id="deleteModal{{ $seat->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $seat->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel{{ $seat->id }}">Ülőhely törlése</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Biztos, hogy törölni szeretnéd a <strong>{{ $seat->seat_number }}</strong> ülőhelyet?</p>
                            <p class="text-muted">Ez a művelet nem vonható vissza!</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégsem</button>
                            <form method="POST" action="{{ route('admin.seats.destroy', $seat) }}" style="display:inline;">
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

    <div class="d-flex justify-content-center mt-4">
        <nav aria-label="Lapozás">
            <ul class="pagination pagination-sm mb-0 shadow-sm" style="border-radius: 8px;">
                @if ($seats->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link border-0 text-muted bg-light">«</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link border-0 text-dark bg-light" href="{{ $seats->previousPageUrl() }}" rel="prev">«</a>
                    </li>
                @endif

                @foreach ($seats->getUrlRange(1, $seats->lastPage()) as $page => $url)
                    <li class="page-item {{ $page == $seats->currentPage() ? 'active' : '' }}">
                        <a class="page-link border-0 {{ $page == $seats->currentPage() ? 'bg-primary text-white' : 'text-dark bg-light' }}" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach

                @if ($seats->hasMorePages())
                    <li class="page-item">
                        <a class="page-link border-0 text-dark bg-light" href="{{ $seats->nextPageUrl() }}" rel="next">»</a>
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
