@extends('layouts.admin')
@section('title', 'Ban User Discord')
@section('content')
<div class="container">
    <h2>Ban User dari Server Discord</h2>
    <form method="POST" action="{{ route('admin.discord.ban') }}">
        @csrf
        <!-- Guild ID dihilangkan, tidak perlu input ini -->
        <div class="mb-3">
            <label>Pilih User Discord</label>
            <select name="user_id" class="form-control select2-user" required>
                <option value="">-- Pilih User --</option>
                @foreach($users as $user)
                    <option value="{{ $user['id'] }}" {{ old('user_id') == $user['id'] ? 'selected' : '' }}>
                        {{ $user['username'] ?? 'Unknown' }}#{{ $user['discriminator'] ?? '' }} ({{ $user['id'] }})
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Pilih user yang akan di-ban. Daftar diambil otomatis dari bot Discord.</small>
        </div>
        <div class="mb-3">
            <label>Alasan (opsional)</label>
            <input type="text" name="reason" class="form-control">
        </div>
        <button type="submit" class="btn btn-danger">Ban User</button>
    </form>
</div>

<!-- jQuery & Select2 CDN -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(function() {
        $('.select2-user').select2({ width: '100%', placeholder: '-- Pilih User --' });
    });
</script>
@endsection
