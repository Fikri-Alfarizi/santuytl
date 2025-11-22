@extends('layouts.app')

@section('title', 'Community Dashboard')

@section('content')
<div class="container" style="padding: 40px 20px; max-width: 1200px; margin: 0 auto;">
    <div class="dashboard-header" style="text-align: center; margin-bottom: 40px;">
        <h1 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 10px;">
            <i class="fab fa-discord" style="color: #5865F2;"></i> Community Dashboard
        </h1>
        <p style="color: var(--text-muted); font-size: 1.1rem;">Statistik real-time server Discord kami</p>
    </div>

    @if($error)
        <div class="alert alert-danger" style="padding: 20px; border-radius: 10px; background-color: rgba(231, 76, 60, 0.1); border: 1px solid #e74c3c; color: #e74c3c; margin-bottom: 30px;">
            <i class="fas fa-exclamation-triangle"></i> {{ $error }}
        </div>
    @endif

    @if($stats)
        <!-- Server Header -->
        <div class="server-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px; padding: 30px; margin-bottom: 30px; text-align: center; color: white;">
            @if($stats['guild_icon'])
                <img src="{{ $stats['guild_icon'] }}" alt="Server Icon" style="width: 80px; height: 80px; border-radius: 50%; margin-bottom: 15px; border: 3px solid white;">
            @endif
            <h2 style="font-size: 2rem; margin-bottom: 5px;">{{ $stats['guild_name'] }}</h2>
            <p style="opacity: 0.9;">Dibuat pada {{ \Carbon\Carbon::parse($stats['created_at'])->format('d M Y') }}</p>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <!-- Total Members -->
            <div class="stat-card" style="background-color: var(--light-bg); border-radius: 12px; padding: 25px; text-align: center; transition: transform 0.3s;">
                <div class="stat-icon" style="font-size: 3rem; margin-bottom: 10px; color: #5865F2;">
                    <i class="fas fa-users"></i>
                </div>
                <h3 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 5px; color: var(--primary-color);">
                    {{ number_format($stats['member_count']) }}
                </h3>
                <p style="color: var(--text-muted); font-size: 1rem;">Total Members</p>
            </div>

            <!-- Online Members -->
            <div class="stat-card" style="background-color: var(--light-bg); border-radius: 12px; padding: 25px; text-align: center; transition: transform 0.3s;">
                <div class="stat-icon" style="font-size: 3rem; margin-bottom: 10px; color: #43b581;">
                    <i class="fas fa-circle"></i>
                </div>
                <h3 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 5px; color: #43b581;">
                    {{ number_format($stats['online_count']) }}
                </h3>
                <p style="color: var(--text-muted); font-size: 1rem;">Online Now</p>
            </div>

            <!-- Server Boost -->
            <div class="stat-card" style="background-color: var(--light-bg); border-radius: 12px; padding: 25px; text-align: center; transition: transform 0.3s;">
                <div class="stat-icon" style="font-size: 3rem; margin-bottom: 10px; color: #ff73fa;">
                    <i class="fas fa-rocket"></i>
                </div>
                <h3 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 5px; color: #ff73fa;">
                    Level {{ $stats['boost_level'] }}
                </h3>
                <p style="color: var(--text-muted); font-size: 1rem;">{{ $stats['boost_count'] }} Boosts</p>
            </div>

            <!-- Channels -->
            <div class="stat-card" style="background-color: var(--light-bg); border-radius: 12px; padding: 25px; text-align: center; transition: transform 0.3s;">
                <div class="stat-icon" style="font-size: 3rem; margin-bottom: 10px; color: #faa61a;">
                    <i class="fas fa-hashtag"></i>
                </div>
                <h3 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 5px; color: #faa61a;">
                    {{ $stats['channel_count'] }}
                </h3>
                <p style="color: var(--text-muted); font-size: 1rem;">
                    {{ $stats['text_channel_count'] }} Text, {{ $stats['voice_channel_count'] }} Voice
                </p>
            </div>

            <!-- Roles -->
            <div class="stat-card" style="background-color: var(--light-bg); border-radius: 12px; padding: 25px; text-align: center; transition: transform 0.3s;">
                <div class="stat-icon" style="font-size: 3rem; margin-bottom: 10px; color: #99aab5;">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 5px; color: #99aab5;">
                    {{ $stats['role_count'] }}
                </h3>
                <p style="color: var(--text-muted); font-size: 1rem;">Server Roles</p>
            </div>
        </div>

        <!-- Join Discord CTA -->
        <div class="join-cta" style="background: linear-gradient(135deg, #5865F2 0%, #7289da 100%); border-radius: 15px; padding: 40px; text-align: center; color: white;">
            <h3 style="font-size: 1.8rem; margin-bottom: 15px;">Belum Bergabung?</h3>
            <p style="font-size: 1.1rem; margin-bottom: 25px; opacity: 0.9;">
                Bergabunglah dengan {{ number_format($stats['member_count']) }} member lainnya di server Discord kami!
            </p>
            <a href="https://discord.gg/YOUR_INVITE_CODE" target="_blank" class="btn btn-light" style="padding: 15px 40px; font-size: 1.1rem; border-radius: 50px; text-decoration: none; background: white; color: #5865F2; font-weight: 600; display: inline-block; transition: transform 0.3s;">
                <i class="fab fa-discord"></i> Join Discord Server
            </a>
        </div>
    @endif
</div>

<style>
.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.btn-light:hover {
    transform: scale(1.05);
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .dashboard-header h1 {
        font-size: 2rem;
    }
}
</style>
@endsection
