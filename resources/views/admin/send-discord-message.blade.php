@extends('layouts.admin')
@section('title', 'Kirim Pesan ke Discord')
@section('content')
<div class="container">
    <h1>Kirim Pesan ke Discord</h1>
    <form method="POST" action="{{ route('admin.discord.send-message') }}">
        @csrf
        <div class="mb-3">
            <label for="channel_id" class="form-label">Pilih Channel Discord</label>
            <select class="form-control" id="channel_id" name="channel_id" required>
                <option value="">-- Pilih Channel --</option>
                @foreach($channels as $ch)
                    <option value="{{ $ch['id'] }}" {{ old('channel_id') == $ch['id'] ? 'selected' : '' }}>
                        [{{ $ch['guild'] }}] #{{ $ch['name'] }} ({{ $ch['id'] }})
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Pilih channel tujuan pesan. Daftar diambil otomatis dari bot Discord.</small>
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Pesan</label>
            <textarea class="form-control" id="message" name="message" rows="4" required placeholder="Tulis pesan untuk dikirim ke Discord..."></textarea>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fab fa-discord"></i> Kirim ke Discord</button>
    </form>
    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
    @endif
</div>
@endsection
