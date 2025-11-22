@extends('layouts.admin')
@section('title', 'Assign Role Discord')
@section('content')
<div class="container">
    <h2>Assign Role ke User Discord</h2>
    <form method="POST" action="{{ route('admin.discord.assign-role') }}">
        @csrf
        <!-- Guild ID dihilangkan, tidak perlu input ini -->
        <div class="mb-3">
            <label>Pilih User Discord</label>
            <select name="user_id" class="form-control select2-user" required>
                <option value="">-- Pilih User --</option>
                @foreach($users as $user)
                    <option value="{{ $user['id'] }}" {{ old('user_id') == $user['id'] ? 'selected' : '' }}>
                        {{ $user['username'] ?? 'User' }}@if(!empty($user['discriminator']))#{{ $user['discriminator'] }}@endif ({{ $user['id'] }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Pilih Role Discord</label>
            <select name="role_id" class="form-control select2-role" required>
                <option value="">-- Pilih Role --</option>
                @foreach($roles as $role)
                    <option value="{{ $role['id'] }}" {{ old('role_id') == $role['id'] ? 'selected' : '' }}>
                        [{{ $role['guild'] }}] {{ $role['name'] }} ({{ $role['id'] }})
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-success">Assign Role</button>
    </form>
</div>
<!-- jQuery & Select2 CDN -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(function() {
        $('.select2-user').select2({ width: '100%', placeholder: '-- Pilih User --' });
        $('.select2-role').select2({ width: '100%', placeholder: '-- Pilih Role --' });
    });
</script>
@endsection
