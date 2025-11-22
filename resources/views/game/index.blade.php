@extends('layouts.app')
@section('title', 'GameHub')
@section('content')
<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-gamepad"></i> GameHub</h2>
    <div class="row row-cols-1 row-cols-md-4 g-4">
        @forelse($games as $game)
            <div class="col">
                <div class="card h-100 shadow-sm">
                    @if($game->image)
                        <img src="{{ $game->image }}" class="card-img-top" alt="{{ $game->title }}" style="object-fit:cover; height:180px;">
                    @else
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height:180px;">No Image</div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $game->title }}</h5>
                        <p class="card-text small">{{ Str::limit($game->description, 80) }}</p>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <a href="{{ $game->link }}" class="btn btn-primary w-100" target="_blank"><i class="fas fa-download"></i> Download</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted">Belum ada game.</div>
        @endforelse
    </div>
    <div class="mt-4 d-flex justify-content-center">
        {{ $games->links('vendor.pagination.custom') }}
    </div>
</div>
@endsection
