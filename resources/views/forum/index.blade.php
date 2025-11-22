@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-white mb-4">Community Forum</h1>
        <p class="text-gray-400 text-lg">Discuss, share, and learn with others.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($categories as $category)
        <a href="{{ route('forum.category.show', $category->slug) }}" class="block bg-gray-800 rounded-xl p-6 border border-gray-700 shadow-lg hover:border-blue-500/50 hover:bg-gray-750 transition-all group">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-lg bg-blue-600/20 flex items-center justify-center text-blue-400 group-hover:text-blue-300 group-hover:bg-blue-600/30 transition-colors">
                    @if($category->icon)
                        <i class="{{ $category->icon }} text-2xl"></i>
                    @else
                        <i class="fas fa-comments text-2xl"></i>
                    @endif
                </div>
                <h3 class="text-xl font-bold text-white group-hover:text-blue-400 transition-colors">{{ $category->name }}</h3>
            </div>
            <p class="text-gray-400 text-sm mb-4">{{ $category->description }}</p>
            <div class="flex items-center justify-between text-xs text-gray-500">
                <span>{{ $category->threads->count() }} Threads</span>
                <span class="text-blue-500 group-hover:translate-x-1 transition-transform">Browse <i class="fas fa-arrow-right ml-1"></i></span>
            </div>
        </a>
        @empty
        <div class="col-span-full text-center py-12 text-gray-500">
            <i class="fas fa-folder-open text-4xl mb-4"></i>
            <p>No forum categories yet.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection