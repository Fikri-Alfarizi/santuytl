@extends('layouts.app')

@section('title', 'My Tickets')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">My Support Tickets</h1>
    <a href="{{ route('tickets.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Buat Ticket Baru
    </a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Created</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->id }}</td>
                    <td>{{ $ticket->subject }}</td>
                    <td>{{ ucfirst($ticket->status) }}</td>
                    <td>{{ $ticket->created_at->format('d M Y H:i') }}</td>
                    <td><a href="{{ route('tickets.show', $ticket) }}" class="btn btn-info btn-sm">Detail</a></td>
                </tr>
            @empty
                <tr><td colspan="5">Belum ada tiket.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
