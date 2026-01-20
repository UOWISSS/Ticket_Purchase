@extends('ticket.layout')

@section('title', 'Jegykezelés - Vonalkód beolvasás')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Jegykezelés - Vonalkód beolvasás</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.tickets.processScan') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="barcode" class="form-label">Vonalkód</label>
                            <input
                                type="text"
                                class="form-control form-control-lg @error('barcode') is-invalid @enderror"
                                id="barcode"
                                name="barcode"
                                placeholder="Olvass be egy vonalkódot..."
                                autofocus
                                value="{{ old('barcode') }}"
                            >
                            @error('barcode')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-barcode me-2"></i>Jegy beolvasása
                        </button>
                    </form>

                    <hr class="my-4">

                    <p class="text-muted small">
                        <strong>Útmutató:</strong><br>
                        1. Helyezd a kurzort a vonalkód beviteli mezőre (automatikusan fókuszban van)<br>
                        2. Olvass be egy jegy vonalkódját a vonalkód-olvasóval<br>
                        3. A rendszer automatikusan feldolgozza a beolvasást
                    </p>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Vissza az admin felületre
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
    }
    .card-header {
        border-radius: 0.5rem 0.5rem 0 0;
    }
    #barcode {
        font-size: 1.2rem;
        padding: 0.75rem;
    }
</style>
@endsection
