@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-center mb-4"><i class="fas fa-user-plus"></i> Daftar Akun Baru</h2>
                    
                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-user-plus"></i> Daftar
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-3">
                        <p>Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
