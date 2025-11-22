@extends('layouts.admin')
@section('title', 'Kirim DM Discord')
@section('content')
<div class="container">
    <h2>Kirim DM ke User Discord</h2>
    <form method="POST" action="{{ route('admin.discord.send-dm') }}">
        @csrf
        <div class="mb-3">
            <label>Pilih User Discord</label>
            <select name="user_id" class="form-control select2-user" required>
                <option value="">-- Pilih User --</option>
                @forelse($users as $user)
                    <option value="{{ $user['id'] }}" {{ old('user_id') == $user['id'] ? 'selected' : '' }}>
                        @if(!empty($user['username']) && !empty($user['discriminator']))
                            {{ $user['username'] }}#{{ $user['discriminator'] }}
                        @else
                            User ID: {{ $user['id'] }}
                        @endif
                    </option>
                @empty
                    <option value="" disabled>Tidak ada user Discord ditemukan</option>
                @endforelse
            </select>
            <small class="text-muted">Pilih user tujuan DM. Daftar diambil otomatis dari bot Discord.</small>
        </div>
<!-- Select2 CDN -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.select2-user').select2({ width: '100%', placeholder: '-- Pilih User --' });
    });
</script>
        <div class="mb-3">
            <label>Pesan</label>
            <textarea name="message" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Kirim DM</button>
    </form>
</div>
@endsection
