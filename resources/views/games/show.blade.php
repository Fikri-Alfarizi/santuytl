@extends('layouts.app')

@section('title', $game->title)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <img src="{{ $game->cover_image ?? 'https://via.placeholder.com/400x500/2c2c2c/ffffff?text=No+Image' }}" class="img-fluid rounded" alt="{{ $game->title }}">
        </div>
        <div class="col-md-8">
            <h1>{{ $game->title }}</h1>
            <p><strong>Developer:</strong> {{ $game->developer ?? 'N/A' }}</p>
            <p><strong>Publisher:</strong> {{ $game->publisher ?? 'N/A' }}</p>
            <p><strong>Rilis:</strong> {{ $game->release_date?->format('d F Y') ?? 'N/A' }}</p>
            <p><strong>Genre:</strong> {{ $game->genres ? implode(', ', $game->genres) : 'N/A' }}</p>
            
            <h3>Deskripsi</h3>
            <p>{{ $game->description ?? 'Tidak ada deskripsi.' }}</p>

            @if($game->password)
                <div class="alert alert-warning">
                    <i class="fas fa-key"></i> <strong>Password:</strong> {{ $game->password }}
                </div>
            @endif

            <div class="mt-4">
                <form action="{{ route('games.download', $game->id) }}" method="POST">
                    @csrf
                    @if($game->access_level === 'vip' && (!Auth::check() || !Auth::user()->isVip()))
                        <button type="button" class="btn btn-warning" disabled>
                            <i class="fas fa-lock"></i> Perlu Akses VIP
                        </button>
                    @else
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-download"></i> Download Sekarang
                        </button>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
