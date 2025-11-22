@extends('layouts.admin')
@section('title', 'Detail User')
@section('content')
<div class="container">
    <h1 class="mb-4">Detail User</h1>
    
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h3>{{ $user['username'] }}#{{ $user['discriminator'] }}</h3>
                    <hr>
                    <p><strong>Discord ID:</strong> {{ $user['id'] }}</p>
                    <p><strong>Role Website:</strong> <span class="badge bg-primary">{{ ucfirst($user['role']) }}</span></p>
                    <p><strong>Level:</strong> {{ ucfirst($user['level']) }}</p>
                    <p><strong>Total XP:</strong> {{ $user['xp'] }}</p>
                    
                    @if($user['joined_website_at'])
                        <p><strong>Bergabung di Website:</strong> {{ $user['joined_website_at']->format('d M Y H:i') }}</p>
                    @else
                        <p><strong>Status:</strong> <span class="text-muted">Belum pernah login ke website</span></p>
                    @endif
                    
                    @if($user['joined_discord_at'])
                        <p><strong>Bergabung di Discord:</strong> {{ \Carbon\Carbon::parse($user['joined_discord_at'])->format('d M Y H:i') }}</p>
                    @endif
                    
                    <p><strong>Server Discord:</strong> {{ $user['guild'] }}</p>
                </div>
                <div class="col-md-4 text-center">
                    @if($user['avatar'])
                        <img src="https://cdn.discordapp.com/avatars/{{ $user['id'] }}/{{ $user['avatar'] }}.png?size=256" 
                             alt="{{ $user['username'] }}" 
                             class="img-fluid rounded-circle mb-3" 
                             style="max-width: 150px; border: 3px solid var(--primary-color);">
                    @else
                        <img src="https://cdn.discordapp.com/embed/avatars/0.png" 
                             alt="Default Avatar" 
                             class="img-fluid rounded-circle mb-3" 
                             style="max-width: 150px; border: 3px solid var(--primary-color);">
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    @if(!empty($user['discord_roles']))
    <div class="card mb-4">
        <div class="card-body">
            <h4>Role Discord</h4>
            <hr>
            <div class="d-flex flex-wrap gap-2">
                @foreach($user['discord_roles'] as $role)
                    <span class="badge" style="background-color: #{{ $role['color'] }}; color: #fff; font-size: 0.9em;">
                        {{ $role['name'] }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>
    @endif
    
    <a href="{{ route('admin.users') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pengguna
    </a>
</div>
@endsection
