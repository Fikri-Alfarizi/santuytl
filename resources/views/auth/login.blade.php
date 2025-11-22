@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-center mb-4"><i class="fas fa-sign-in-alt"></i> Masuk</h2>
                    
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                        </div>
                        
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">Ingat Saya</label>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt"></i> Masuk
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-3">
                        <p>Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
