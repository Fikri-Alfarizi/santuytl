@extends('layouts.app')

@section('title', $event->title . ' - Event')

@section('content')
<div class="container">
    {{-- Header Event --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <a href="{{ route('events.index') }}" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Event
            </a>
            
            <div class="card" style="background-color: var(--light-bg); border-radius: 10px; overflow: hidden;">
                @if($event->banner_image)
                    <img src="{{ asset('storage/' . $event->banner_image) }}" class="card-img-top" alt="{{ $event->title }}" style="height: 300px; object-fit: cover;">
                @else
                    <div class="card-img-top" style="height: 300px; background: linear-gradient(135deg, var(--primary-color), var(--accent-color)); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-trophy" style="font-size: 6em; color: rgba(255,255,255,0.3);"></i>
                    </div>
                @endif
                
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h2>{{ $event->title }}</h2>
                        @if($event->is_active && now()->lt($event->ends_at))
                            <span class="badge bg-success" style="font-size: 1em;">
                                <i class="fas fa-circle-notch fa-spin"></i> Aktif
                            </span>
                        @else
                            <span class="badge bg-secondary" style="font-size: 1em;">
                                <i class="fas fa-check-circle"></i> Selesai
                            </span>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        @if($event->access_level === 'vip')
                            <span class="badge" style="background-color: var(--warning-color); font-size: 0.9em;">
                                <i class="fas fa-crown"></i> VIP Only
                            </span>
                        @endif
                        <span class="badge" style="background-color: var(--accent-color); font-size: 0.9em;">
                            <i class="fas fa-tag"></i> {{ ucfirst($event->type) }}
                        </span>
                    </div>
                    
                    <p class="lead">{{ $event->description }}</p>
                    
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <p><strong><i class="fas fa-calendar-start"></i> Mulai:</strong><br>
                            {{ $event->starts_at ? $event->starts_at->format('d M Y, H:i') : 'TBA' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong><i class="fas fa-calendar-check"></i> Berakhir:</strong><br>
                            {{ $event->ends_at ? $event->ends_at->format('d M Y, H:i') : 'TBA' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong><i class="fas fa-users"></i> Total Peserta:</strong><br>
                            {{ $event->participants->count() }} orang</p>
                        </div>
                    </div>
                    
                    @if($event->xp_reward || $event->badge_reward || $event->role_reward || $event->vip_days_reward)
                        <div class="alert alert-info mt-3">
                            <h5><i class="fas fa-gift"></i> Hadiah:</h5>
                            <ul class="mb-0">
                                @if($event->xp_reward)
                                    <li><i class="fas fa-star"></i> {{ $event->xp_reward }} XP</li>
                                @endif
                                @if($event->coin_reward)
                                    <li><i class="fas fa-coins"></i> {{ $event->coin_reward }} Coins</li>
                                @endif
                                @if($event->badge_reward)
                                    <li><i class="fas fa-award"></i> Badge: {{ $event->badge_reward }}</li>
                                @endif
                                @if($event->role_reward)
                                    <li><i class="fas fa-shield-alt"></i> Role: {{ $event->role_reward }}</li>
                                @endif
                                @if($event->vip_days_reward)
                                    <li><i class="fas fa-crown"></i> VIP {{ $event->vip_days_reward }} hari</li>
                                @endif
                            </ul>
                        </div>
                    @endif
                    
                    {{-- Tombol Aksi --}}
                    @auth
                        @if($event->is_active && now()->lt($event->ends_at))
                            @if(!$participation)
                                <form action="{{ route('events.register', $event->id) }}" method="POST" class="mt-3">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fas fa-user-plus"></i> Daftar Event
                                    </button>
                                </form>
                            @elseif($participation->status === 'registered')
                                @if($event->auto_reward)
                                    <form action="{{ route('events.claim', $event->id) }}" method="POST" class="mt-3">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-gift"></i> Claim Reward
                                        </button>
                                    </form>
                                @else
                                    <div class="alert alert-warning mt-3">
                                        <i class="fas fa-info-circle"></i> Anda sudah terdaftar. Silakan submit entry Anda!
                                    </div>
                                @endif
                                {{-- Form Submit bisa ditambahkan di sini sesuai type event --}}
                            @elseif($participation->status === 'submitted')
                                <div class="alert alert-success mt-3">
                                    <i class="fas fa-check-circle"></i> Entry Anda sudah disubmit. Tunggu hasil voting!
                                </div>
                            @elseif($participation->status === 'claimed')
                                <div class="alert alert-success mt-3">
                                    <i class="fas fa-check-circle"></i> Reward claimed!
                                </div>
                            @endif
                        @endif
                    @else
                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-sign-in-alt"></i> Silakan <a href="{{ route('login') }}">login</a> untuk mengikuti event ini.
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
    
    {{-- Leaderboard --}}
    @if($topParticipants->count() > 0)
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card" style="background-color: var(--light-bg); border-radius: 10px;">
                    <div class="card-body">
                        <h3><i class="fas fa-trophy"></i> Leaderboard - Top 10</h3>
                        <hr>
                        
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="80">Rank</th>
                                        <th>Peserta</th>
                                        <th width="120" class="text-center">Votes</th>
                                        <th width="150" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topParticipants as $index => $participant)
                                        <tr style="background-color: {{ $index === 0 ? 'rgba(255, 215, 0, 0.1)' : ($index === 1 ? 'rgba(192, 192, 192, 0.1)' : ($index === 2 ? 'rgba(205, 127, 50, 0.1)' : 'transparent')) }};">
                                            <td>
                                                @if($index === 0)
                                                    <span class="badge" style="background-color: gold; color: #000; font-size: 1.2em;">
                                                        <i class="fas fa-crown"></i> #1
                                                    </span>
                                                @elseif($index === 1)
                                                    <span class="badge" style="background-color: silver; color: #000; font-size: 1.1em;">
                                                        <i class="fas fa-medal"></i> #2
                                                    </span>
                                                @elseif($index === 2)
                                                    <span class="badge" style="background-color: #CD7F32; color: #fff; font-size: 1.1em;">
                                                        <i class="fas fa-medal"></i> #3
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        #{{ $index + 1 }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($participant->user->discord_avatar_url)
                                                        <img src="{{ $participant->user->discord_avatar_url }}" alt="{{ $participant->user->username }}" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                                                    @endif
                                                    <div>
                                                        <strong>{{ $participant->user->username }}</strong><br>
                                                        <small class="text-muted">Level {{ $participant->user->level }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary" style="font-size: 1.1em;">
                                                    <i class="fas fa-heart"></i> {{ $participant->votes }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @auth
                                                    @if($event->is_active && now()->lt($event->ends_at))
                                                        @php
                                                            $hasVoted = \App\Models\EventVote::where('event_id', $event->id)
                                                                ->where('voter_id', Auth::id())
                                                                ->exists();
                                                        @endphp
                                                        
                                                        @if(!$hasVoted && $participant->user_id !== Auth::id())
                                                            <form action="{{ route('events.vote', $participant->id) }}" method="POST" style="display: inline;">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-primary">
                                                                    <i class="fas fa-heart"></i> Vote
                                                                </button>
                                                            </form>
                                                        @elseif($hasVoted)
                                                            <span class="badge bg-success">
                                                                <i class="fas fa-check"></i> Voted
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary">
                                                                <i class="fas fa-user"></i> You
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-secondary">Voting Selesai</span>
                                                    @endif
                                                @else
                                                    <a href="{{ route('login') }}" class="btn btn-sm btn-secondary">Login to Vote</a>
                                                @endauth
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
