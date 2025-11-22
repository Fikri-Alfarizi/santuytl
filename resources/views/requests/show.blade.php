@extends('layouts.app')

@section('title', 'Detail Permintaan Game')

@section('content')
<div class="container">
    <h1 class="mb-4">Detail Permintaan Game</h1>
    <div class="card">
        <div class="card-body">
            <h3>{{ $request->title }}</h3>
            <p><strong>Status:</strong> {{ ucfirst($request->status) }}</p>
            <p><strong>Dibuat pada:</strong> {{ $request->created_at->format('d F Y H:i') }}</p>
            <hr>
            <p>{{ $request->description }}</p>
            <a href="{{ route('requests.index') }}" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
</div>
@endsection
