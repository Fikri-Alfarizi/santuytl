@extends('layouts.app')

@section('title', 'Cari Game')

@section('content')
<div class="container">
    <h1 class="mb-4">Hasil Pencarian Game</h1>
    <form action="{{ route('games.search') }}" method="GET" class="mb-4">
        <input type="text" name="q" class="form-control" placeholder="Cari game..." value="{{ request('q') }}">
    </form>
    <div class="row">
        @forelse ($games as $game)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="{{ $game->cover_image ?? 'https://via.placeholder.com/400x225/2c2c2c/ffffff?text=No+Image' }}" class="card-img-top" alt="{{ $game->title }}">
                    @if($game->access_level === 'vip')
                        <span class="badge-custom"><i class="fas fa-crown"></i> VIP</span>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $game->title }}</h5>
                        <p class="card-text">{{ Str::limit($game->description, 100) }}</p>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <small><i class="fas fa-download"></i> {{ $game->download_count }} unduhan</small>
                            <a href="{{ route('games.show', $game->slug) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-info-circle"></i> Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>Tidak ada game yang ditemukan.</p>
        @endforelse
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $games->links() }}
    </div>
</div>
@endsection
