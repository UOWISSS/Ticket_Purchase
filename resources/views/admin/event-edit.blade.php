@extends('ticket.layout')

@section('title', 'Esemény módosítása')

@section('content')
<div class="container py-4">
    <h1>Esemény módosítása</h1>

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

    @if($salesHaveStarted)
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Figyelem!</strong> A jegyárusítás már megkezdődött. Csak a megnevezés, leírás és borítókép módosítható.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.events.update', $event) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Cím</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" required value="{{ old('title', $event->title) }}">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Leírás</label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ old('description', $event->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        @if(!$salesHaveStarted)
            <div class="mb-3">
                <label for="event_date_at" class="form-label">Esemény időpontja</label>
                <input type="datetime-local" class="form-control @error('event_date_at') is-invalid @enderror" id="event_date_at" name="event_date_at" required value="{{ old('event_date_at', $event->event_date_at->format('Y-m-d\TH:i')) }}">
                @error('event_date_at')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="sale_start_at" class="form-label">Jegyárusítás kezdete</label>
                <input type="datetime-local" class="form-control @error('sale_start_at') is-invalid @enderror" id="sale_start_at" name="sale_start_at" required value="{{ old('sale_start_at', $event->sale_start_at->format('Y-m-d\TH:i')) }}">
                @error('sale_start_at')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="sale_end_at" class="form-label">Jegyárusítás vége</label>
                <input type="datetime-local" class="form-control @error('sale_end_at') is-invalid @enderror" id="sale_end_at" name="sale_end_at" required value="{{ old('sale_end_at', $event->sale_end_at->format('Y-m-d\TH:i')) }}">
                @error('sale_end_at')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="max_number_allowed" class="form-label">Max. jegy/fő</label>
                <input type="number" class="form-control @error('max_number_allowed') is-invalid @enderror" id="max_number_allowed" name="max_number_allowed" min="1" required value="{{ old('max_number_allowed', $event->max_number_allowed) }}">
                @error('max_number_allowed')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_dynamic_price" name="is_dynamic_price" value="1" {{ old('is_dynamic_price', $event->is_dynamic_price) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_dynamic_price">Dinamikus árképzés engedélyezése</label>
            </div>
        @endif

        <div class="mb-3">
            <label for="image" class="form-label">Borítókép URL (opcionális)</label>
            <input type="url" class="form-control @error('image') is-invalid @enderror" id="image" name="image" value="{{ old('image', $event->image) }}">
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Módosítás</button>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary ms-2">Mégsem</a>
    </form>
</div>
@endsection
