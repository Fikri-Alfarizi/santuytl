@extends('layouts.app')

@section('title', 'Detail Ticket')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Detail Ticket #{{ $ticket->id }}</h1>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">{{ $ticket->subject }}</h5>
            <p class="card-text">{{ $ticket->message }}</p>
            <p class="mb-1"><strong>Status:</strong> {{ ucfirst($ticket->status) }}</p>
            <p class="mb-1"><strong>Dibuat:</strong> {{ $ticket->created_at->format('d M Y H:i') }}</p>
            @if($ticket->discord_ticket_id)
                <p class="mb-1"><strong>Discord Ticket:</strong> <span class="text-success">Terkirim ke Discord</span></p>
            @endif
        </div>
    </div>
    <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Kembali ke Daftar Ticket</a>
</div>
@endsection
