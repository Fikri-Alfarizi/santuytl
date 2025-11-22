@extends('layouts.app')

@section('title', 'Buat Ticket Baru')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Buat Ticket Support Baru</h1>
    <form method="POST" action="{{ route('tickets.store') }}">
        @csrf
        <div class="mb-3">
            <label for="subject" class="form-label">Subject</label>
            <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" required>
            @error('subject')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Pesan</label>
            <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
            @error('message')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane"></i> Kirim Ticket</button>
        <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
