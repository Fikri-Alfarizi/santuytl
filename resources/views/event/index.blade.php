@extends('layouts.app')
@section('title', 'Event')
@section('content')
<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-calendar-alt"></i> Event</h2>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @forelse($events as $event)
            <div class="col">
                <div class="card h-100 shadow-sm">
                    @if($event->image)
                        <img src="{{ $event->image }}" class="card-img-top" alt="{{ $event->title }}" style="object-fit:cover; height:160px;">
                    @else
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height:160px;">No Image</div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $event->title }}</h5>
                        <p class="card-text small">{{ Str::limit($event->description, 100) }}</p>
                        @if($event->date)
                            <div class="text-muted small mb-1"><i class="fas fa-clock"></i>
                                @if($event->date instanceof \Carbon\Carbon || $event->date instanceof DateTime)
                                    {{ $event->date->format('d M Y H:i') }}
                                @else
                                    {{ $event->date }}
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted">Belum ada event.</div>
        @endforelse
    </div>
    <div class="mt-4 d-flex justify-content-center">
        {{ $events->links() }}
    </div>
</div>
@endsection
