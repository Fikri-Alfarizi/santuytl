@extends('layouts.app')

@section('title', 'VIP Content')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-3xl font-bold">VIP Content & Downloads</h1>
    @if (!$isVip)
        <div class="alert alert-warning mb-4">
            <strong>VIP Only!</strong> Konten ini hanya dapat diakses oleh member dengan role VIP di Discord. Silakan upgrade keanggotaan Anda untuk mendapatkan akses eksklusif.
        </div>
    @endif
    <div class="row">
        @forelse($games as $game)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if($game->image)
                        <img src="{{ $game->image }}" class="card-img-top" alt="{{ $game->title }}">
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $game->title }}</h5>
                        <p class="card-text">{{ Str::limit($game->description, 100) }}</p>
                        @if($isVip)
                            <a href="{{ $game->link }}" class="btn btn-success mt-auto" target="_blank">
                                <i class="fas fa-download me-1"></i>Download
                            </a>
                        @else
                            <button class="btn btn-secondary mt-auto" disabled>
                                <i class="fas fa-lock me-1"></i>VIP Only
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p>Tidak ada konten VIP yang tersedia saat ini.</p>
        @endforelse
    </div>
</div>
@endsection
