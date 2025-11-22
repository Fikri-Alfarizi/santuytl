@extends('layouts.app')

@section('title', 'Dashboard Saya')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Dashboard Saya</h1>
    @if($error)
        <div class="alert alert-danger">{{ $error }}</div>
    @elseif($stats)
        <div class="row mb-4">
            <div class="col-md-3 text-center">
                <img src="{{ $stats['avatar'] ?? '' }}" class="rounded-circle mb-2" width="96" height="96" alt="Avatar">
                <h4>{{ $stats['username'] }}#{{ $stats['discriminator'] }}</h4>
                <div class="text-muted">Bergabung: {{ $stats['joined_at'] ? \Carbon\Carbon::parse($stats['joined_at'])->format('d M Y') : '-' }}</div>
            </div>
            <div class="col-md-9">
                <div class="row mb-2">
                    <div class="col-6 col-lg-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="h2">{{ $stats['xp'] }}</div>
                                <div class="text-muted">XP</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="h2">{{ $stats['level'] }}</div>
                                <div class="text-muted">Level</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="h2">{{ $stats['messages'] }}</div>
                                <div class="text-muted">Pesan</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="h2">{{ $stats['voice_minutes'] }}</div>
                                <div class="text-muted">Menit Voice</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <strong>Role Discord:</strong>
                    @foreach($stats['roles'] as $role)
                        <span class="badge bg-primary">{{ $role }}</span>
                    @endforeach
                </div>
                <div class="mb-3">
                    <strong>Badge:</strong>
                    @foreach($stats['badges'] as $badge)
                        <span class="badge bg-warning text-dark">{{ $badge['icon'] }} {{ $badge['name'] }}</span>
                    @endforeach
                </div>
                <div>
                    <strong>Pencapaian:</strong>
                    <ul>
                        @foreach($stats['achievements'] as $ach)
                            <li>{{ $ach['name'] }} <span class="text-muted">({{ $ach['date'] }})</span></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">Tidak ada data statistik ditemukan.</div>
    @endif
</div>
@endsection
