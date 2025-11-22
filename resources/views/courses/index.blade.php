@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-white mb-4">Learning Center</h1>
        <p class="text-gray-400 text-lg">Master new skills and earn rewards.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($courses as $course)
        <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg border border-gray-700 hover:border-blue-500 transition-all group">
            <div class="h-48 bg-gray-700 relative overflow-hidden">
                @if($course->thumbnail)
                    <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-600 to-purple-700">
                        <i class="fas fa-graduation-cap text-6xl text-white/20"></i>
                    </div>
                @endif
                <div class="absolute top-4 right-4 bg-black/50 backdrop-blur px-3 py-1 rounded-full text-xs text-white border border-white/10">
                    {{ $course->chapters->count() }} Chapters
                </div>
            </div>
            
            <div class="p-6">
                <h3 class="text-xl font-bold text-white mb-2 group-hover:text-blue-400 transition-colors">{{ $course->title }}</h3>
                <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ $course->description }}</p>
                
                <div class="flex items-center gap-4 mb-6 text-sm">
                    @if($course->xp_reward)
                        <span class="text-green-400 flex items-center gap-1"><i class="fas fa-star"></i> {{ $course->xp_reward }} XP</span>
                    @endif
                    @if($course->coin_reward)
                        <span class="text-yellow-400 flex items-center gap-1"><i class="fas fa-coins"></i> {{ $course->coin_reward }} Coins</span>
                    @endif
                </div>

                <a href="{{ route('courses.show', $course->slug) }}" class="block w-full bg-gray-700 hover:bg-blue-600 text-white text-center font-bold py-3 rounded-lg transition-colors">
                    Start Learning
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 text-gray-500">
            <i class="fas fa-book-open text-4xl mb-4"></i>
            <p>No courses available at the moment.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
