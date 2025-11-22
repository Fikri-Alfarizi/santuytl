@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <div>
                <h1><i class="fas fa-tachometer-alt"></i> Dashboard, {{ Auth::user()->username }}!</h1>
                <p class="text-muted">Selamat datang kembali. Ini adalah pusat kontrol Anda di GameHub.</p>
            </div>
            <div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary" style="margin-right:8px;">
                    <i class="fab fa-discord"></i> Kontrol Bot Discord
                </a>
            </div>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Kartu Statistik Utama -->
    <div class="row">
        <div class="col-md-3">
            <div class="card text-center" style="background-color: var(--light-bg); border-radius: 10px; padding: 20px; margin-bottom: 20px; border-left: 4px solid var(--primary-color);">
                <h5><i class="fas fa-user"></i> Level</h5>
                <h3 style="text-transform: capitalize;">{{ Auth::user()->level }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center" style="background-color: var(--light-bg); border-radius: 10px; padding: 20px; margin-bottom: 20px; border-left: 4px solid var(--success-color);">
                <h5><i class="fas fa-shield-alt"></i> Role Discord</h5>
                @if(Auth::user()->discord_roles && count(Auth::user()->discord_roles) > 0)
                    @php
                        $topRole = Auth::user()->discord_roles[0];
                    @endphp
                    <h3 style="font-size: 1.2em;">
                        <span class="badge" style="background: {{ $topRole['color'] }}; color: #fff;">
                            {{ $topRole['name'] }}
                        </span>
                    </h3>
                @else
                    <h3>-</h3>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center" style="background-color: var(--light-bg); border-radius: 10px; padding: 20px; margin-bottom: 20px; border-left: 4px solid var(--warning-color);">
                <h5><i class="fas fa-crown"></i> Status VIP</h5>
                <h3>
                    @if(Auth::user()->isVip())
                        <span style="color: var(--warning-color);">Aktif</span>
                    @else
                        <span style="color: var(--text-muted);">Standar</span>
                    @endif
                </h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center" style="background-color: var(--light-bg); border-radius: 10px; padding: 20px; margin-bottom: 20px; border-left: 4px solid var(--accent-color);">
                <h5><i class="fas fa-star"></i> Total XP</h5>
                <h3>{{ Auth::user()->stats->xp ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Badge/Lencana User -->
        <div class="col-md-12 mb-4">
            <div class="card" style="background-color: var(--light-bg); border-radius: 10px; padding: 20px;">
                <h3><i class="fas fa-award"></i> Lencana Anda</h3>
                <hr style="border-color: var(--border-color);">
                <div class="d-flex flex-wrap gap-3">
                    @forelse(Auth::user()->badges as $badge)
                        <span title="{{ $badge->description }}" style="display: inline-block; min-width: 100px;">
                            <i class="{{ $badge->icon }}" style="color: {{ $badge->color ?? '#FFD700' }}; font-size: 2em;"></i><br>
                            <small>{{ $badge->name }}</small>
                        </span>
                    @empty
                        <span class="text-muted">Belum ada lencana</span>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Informasi Akun Discord -->
        <div class="col-md-6">
            <div class="card" style="background-color: var(--light-bg); border-radius: 10px; padding: 20px;">
                <h3><i class="fab fa-discord"></i> Informasi Akun Discord</h3>
                <hr style="border-color: var(--border-color);">
                <div class="row mt-3">
                    <div class="col-md-8">
                        <p><strong>Username:</strong> {{ Auth::user()->discord_username }}#{{ Auth::user()->discord_discriminator }}</p>
                        <p><strong>ID Discord:</strong> {{ Auth::user()->discord_id }}</p>
                        <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <img src="{{ Auth::user()->avatar ?? Auth::user()->discord_avatar_url ?? 'https://cdn.discordapp.com/embed/avatars/0.png' }}" alt="{{ Auth::user()->name }}" style="width: 80px; height: 80px; border-radius: 50%; border: 3px solid var(--primary-color);">
                    </div>
                </div>
            </div>
        </div>

        <!-- Progres Anda -->
        <div class="col-md-6">
            <div class="card" style="background-color: var(--light-bg); border-radius: 10px; padding: 20px;">
                <h3><i class="fas fa-chart-line"></i> Progres Anda</h3>
                <hr style="border-color: var(--border-color);">
                <div class="mt-3">
                    <p><strong>Level {{ Auth::user()->stats->level ?? 1 }}: {{ Auth::user()->stats->xp ?? 0 }} / {{ Auth::user()->stats->xp_to_next_level ?? 100 }} XP</strong></p>
                    <div class="progress" style="height: 25px; background-color: var(--darker-bg);">
                        @php
                            $currentXp = Auth::user()->stats->xp ?? 0;
                            $xpToNext = Auth::user()->stats->xp_to_next_level ?? 100;
                            $progressPercentage = ($xpToNext > 0) ? ($currentXp / $xpToNext) * 100 : 100;
                        @endphp
                        <div class="progress-bar" role="progressbar" style="width: {{ $progressPercentage }}%; background-color: var(--primary-color);" aria-valuenow="{{ $currentXp }}" aria-valuemin="0" aria-valuemax="{{ $xpToNext }}">
                            {{ number_format($progressPercentage, 1) }}%
                        </div>
                    </div>
                    <small class="text-muted mt-2 d-block">Naik level dengan berinteraksi di server Discord!</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .progress {
        border-radius: 5px;
        overflow: hidden;
    }
    .progress-bar {
        color: white;
        text-align: center;
        line-height: 20px;
        transition: width 0.6s ease;
    }
</style>
@endsection

@section('scripts')
<script>
window.Echo.private('user.{{ Auth::user()->id }}')
    .listen('.xp.updated', (e) => {
        alert(`XP kamu bertambah ${e.xpAmount}!`);
        // TODO: Update DOM XP, level, progress bar sesuai kebutuhan
    });
</script>
@endsection