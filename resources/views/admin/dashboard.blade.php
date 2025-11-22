@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">
    <h1>Dashboard Admin</h1>
    <p class="text-muted">Ringkasan statistik dan aktivitas terbaru di komunitas GameHub.</p>

    <!-- Info Role Discord Admin -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="card" style="background-color: var(--light-bg); border-radius: 10px; padding: 20px;">
                <h5><i class="fab fa-discord"></i> Role Discord Anda</h5>
                @php
                    $adminRoles = Auth::user()->discord_roles ?? [];
                @endphp
                @if($adminRoles && count($adminRoles) > 0)
                    <div>
                        @foreach($adminRoles as $role)
                            @php
                                // Handle both array and string formats
                                $roleName = is_array($role) ? ($role['name'] ?? 'Unknown') : $role;
                            @endphp
                            <span class="badge bg-secondary" style="margin:2px; text-transform:capitalize;">{{ $roleName }}</span>
                        @endforeach
                    </div>
                @else
                    <span class="text-muted">-</span>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card stat-card card-body">
                <h3><i class="fas fa-users"></i> {{ $totalUsers }}</h3>
                <p>Total Pengguna</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card card-body">
                <h3><i class="fas fa-crown" style="color: var(--warning-color);"></i> {{ $totalVipUsers }}</h3>
                <p>Pengguna VIP</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card card-body">
                <h3><i class="fas fa-star"></i> {{ number_format($totalXpGiven) }}</h3>
                <p>Total XP Diberikan</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card card-body">
                <h3><i class="fas fa-exclamation-triangle" style="color: var(--warning-color);"></i> {{ $pendingRequests }}</h3>
                <p>Permintaan Tertunda</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><i class="fas fa-user-plus"></i> Pengguna Terbaru</div>
                <div class="card-body">
                    <ul class="list-group">
                        @forelse ($latestUsers as $user)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $user->name }}</strong><br>
                                    <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                </div>
                                <a href="{{ route('admin.users.details', $user->id) }}" class="btn btn-primary btn-sm">Lihat</a>
                            </li>
                        @empty
                            <li class="list-group-item">Belum ada pengguna baru.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><i class="fas fa-list"></i> Permintaan Game Terbaru</div>
                <div class="card-body">
                    <ul class="list-group">
                        @forelse ($latestRequests as $req)
                            <li class="list-group-item">
                                <strong>{{ $req->title }}</strong> oleh <em>{{ $req->user->name }}</em>
                                <br><small class="text-muted">Status: {{ $req->status }}</small>
                            </li>
                        @empty
                            <li class="list-group-item">Belum ada permintaan baru.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection