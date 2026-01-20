@extends('ticket.layout')

@section('title', 'Új ülőhely')

@section('content')
<div class="container py-4">
    <h1>Új ülőhely létrehozása</h1>

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

    <form method="POST" action="{{ route('admin.seats.store') }}">
        @csrf
        <div class="mb-3">
            <label for="seat_number" class="form-label">Ülőhely azonosító</label>
            <input type="text" class="form-control @error('seat_number') is-invalid @enderror" id="seat_number" name="seat_number" required value="{{ old('seat_number') }}" placeholder="pl. A001">
            @error('seat_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="base_price" class="form-label">Alapár (Ft)</label>
            <input type="number" class="form-control @error('base_price') is-invalid @enderror" id="base_price" name="base_price" min="0" required value="{{ old('base_price', 0) }}" placeholder="pl. 1000">
            @error('base_price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Létrehozás</button>
        <a href="{{ route('admin.seats.index') }}" class="btn btn-secondary ms-2">Mégsem</a>
    </form>
</div>
@endsection
