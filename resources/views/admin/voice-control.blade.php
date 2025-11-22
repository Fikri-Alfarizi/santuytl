@extends('layouts.admin')

@section('title', 'Voice Channel 24/7')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4"><i class="fas fa-microphone"></i> Voice Channel 24/7</h1>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <div class="row">
        {{-- Voice Status Card --}}
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Status Voice</h5>
                </div>
                <div class="card-body">
                    <div id="voice-status">
                        @if($voiceStatus && $voiceStatus['connected'])
                            <div class="alert alert-success">
                                <h6><i class="fas fa-check-circle"></i> Bot Sedang di Voice Channel</h6>
                                <hr>
                                <p class="mb-1"><strong>Channel:</strong> {{ $voiceStatus['channel_name'] }}</p>
                                <p class="mb-1"><strong>Server:</strong> {{ $voiceStatus['guild_name'] }}</p>
                                <p class="mb-1"><strong>Duration:</strong> <span id="duration">{{ gmdate('H:i:s', $voiceStatus['duration']) }}</span></p>
                                <p class="mb-0"><strong>Status:</strong> <span class="badge bg-success">{{ $voiceStatus['status'] }}</span></p>
                            </div>
                        @else
                            <div class="alert alert-secondary">
                                <i class="fas fa-times-circle"></i> Bot tidak sedang di voice channel
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Voice Control Card --}}
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-sliders-h"></i> Kontrol Voice</h5>
                </div>
                <div class="card-body">
                    @if($voiceStatus && $voiceStatus['connected'])
                        {{-- Leave Voice Form --}}
                        <form action="{{ route('admin.discord.voice.leave') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-lg w-100">
                                <i class="fas fa-sign-out-alt"></i> Leave Voice Channel
                            </button>
                        </form>
                    @else
                        {{-- Join Voice Form --}}
                        <form action="{{ route('admin.discord.voice.join') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="channel_id" class="form-label">Pilih Voice Channel</label>
                                <select name="channel_id" id="channel_id" class="form-select" required>
                                    <option value="">-- Pilih Channel --</option>
                                    @foreach($channels as $channel)
                                        <option value="{{ $channel['id'] }}">
                                            {{ $channel['name'] }} ({{ $channel['members'] }} members)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success btn-lg w-100">
                                <i class="fas fa-sign-in-alt"></i> Join Voice Channel
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-question-circle"></i> Informasi</h5>
                </div>
                <div class="card-body">
                    <h6>Fitur Voice 24/7:</h6>
                    <ul>
                        <li>Bot akan tetap online di voice channel 24 jam</li>
                        <li>Auto-reconnect jika terputus</li>
                        <li>Pilih voice channel yang ingin di-join</li>
                        <li>Status akan update otomatis setiap 5 detik</li>
                    </ul>
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-triangle"></i> <strong>Catatan:</strong> 
                        Pastikan bot memiliki permission <code>CONNECT</code> dan <code>SPEAK</code> di voice channel yang dipilih.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-refresh voice status every 5 seconds
setInterval(function() {
    fetch('{{ route("admin.discord.voice.status") }}')
        .then(response => response.json())
        .then(data => {
            let statusHtml = '';
            if (data.connected) {
                statusHtml = `
                    <div class="alert alert-success">
                        <h6><i class="fas fa-check-circle"></i> Bot Sedang di Voice Channel</h6>
                        <hr>
                        <p class="mb-1"><strong>Channel:</strong> ${data.channel_name}</p>
                        <p class="mb-1"><strong>Server:</strong> ${data.guild_name}</p>
                        <p class="mb-1"><strong>Duration:</strong> <span id="duration">${formatDuration(data.duration)}</span></p>
                        <p class="mb-0"><strong>Status:</strong> <span class="badge bg-success">${data.status}</span></p>
                    </div>
                `;
            } else {
                statusHtml = `
                    <div class="alert alert-secondary">
                        <i class="fas fa-times-circle"></i> Bot tidak sedang di voice channel
                    </div>
                `;
            }
            document.getElementById('voice-status').innerHTML = statusHtml;
        })
        .catch(error => console.error('Error fetching voice status:', error));
}, 5000);

function formatDuration(seconds) {
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    const secs = seconds % 60;
    return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
}
</script>
@endsection
