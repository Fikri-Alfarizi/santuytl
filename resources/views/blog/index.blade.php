@extends('layouts.app')
@section('title', 'Blog')
@section('content')
<div class="container">
    <h1 class="mb-4">Artikel Terbaru</h1>
    <div class="row">
        @forelse($posts as $post)
            <div class="col-md-8 offset-md-2 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title"><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h3>
                        <p class="text-muted">Oleh {{ $post->author->name }} pada {{ $post->published_at->format('d F Y') }}</p>
                        <p>{{ Str::limit($post->content, 200) }}</p>
                        <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-primary">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>
        @empty
            <p>Belum ada artikel.</p>
        @endforelse
    </div>
    {{ $posts->links() }}
</div>
@endsection
