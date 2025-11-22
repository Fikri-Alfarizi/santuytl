@extends('layouts.app')
@section('title', $post->title)
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h1 class="card-title">{{ $post->title }}</h1>
                    <p class="text-muted">Oleh {{ $post->author->name }} pada {{ $post->published_at->format('d F Y') }}</p>
                    <div class="mb-3">{!! nl2br(e($post->content)) !!}</div>
                </div>
            </div>
            <a href="{{ route('blog.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali ke Blog</a>
        </div>
    </div>
</div>
@endsection
