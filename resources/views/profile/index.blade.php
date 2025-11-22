@extends('layouts.app')
@section('title', 'Profil Saya')
@section('content')
<div class="container">
    <h1 class="mb-4">Profil Saya</h1>
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="{{ Auth::user()->avatar ?? Auth::user()->discord_avatar_url ?? 'https://cdn.discordapp.com/embed/avatars/0.png' }}" class="rounded-circle mb-3" width="120" height="120" alt="Avatar">
                    <h3>{{ Auth::user()->name }}</h3>
                    <p class="text-muted">{{ Auth::user()->email }}</p>
                    <span class="badge bg-primary">{{ Auth::user()->level ?? 'Warga Baru' }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h4>Informasi Akun</h4>
                    <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                    <p><strong>Level:</strong> {{ Auth::user()->level ?? '-' }}</p>
                    <p><strong>Status VIP:</strong> @if(Auth::user()->isVip()) <span class="text-success">Aktif</span> @else <span class="text-danger">Tidak Aktif</span> @endif</p>
                    <a href="{{ route('vip.index') }}" class="btn btn-warning"><i class="fas fa-crown"></i> Lihat Status VIP</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4>Ubah Password</h4>
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="current_password" class="form-label">Password Lama</label>
                            <input type="password" name="current_password" id="current_password" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <input type="password" name="new_password" id="new_password" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
