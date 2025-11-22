@extends('layouts.app')

@section('title', 'Buat Permintaan Game')

@section('content')
<div class="container">
    <h1 class="mb-4">Buat Permintaan Game</h1>
    <form method="POST" action="{{ route('requests.store') }}">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Judul Game</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="source_link" class="form-label">Link Sumber (opsional)</label>
            <input type="url" class="form-control" id="source_link" name="source_link">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi (opsional)</label>
            <textarea class="form-control" id="description" name="description" rows="4"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Kirim Permintaan</button>
    </form>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Buat Permintaan Game')

@section('content')
<div class="container">
    <h1 class="mb-4">Buat Permintaan Game Baru</h1>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('requests.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="title" class="form-label">Judul Game</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label for="description" class="form-label">Deskripsi Permintaan</label>
                    <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Kirim Permintaan</button>
            </form>
        </div>
    </div>
</div>
@endsection
