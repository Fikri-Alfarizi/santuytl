@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('courses.index') }}" class="text-gray-400 hover:text-white transition-colors flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Back to Courses
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Course Info -->
        <div class="lg:col-span-2">
            <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg border border-gray-700 mb-8">
                <div class="h-64 bg-gray-700 relative">
                    @if($course->thumbnail)
                        <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-600 to-purple-700">
                            <i class="fas fa-graduation-cap text-8xl text-white/20"></i>
                        </div>
                    @endif
                </div>
                <div class="p-8">
                    <h1 class="text-3xl font-bold text-white mb-4">{{ $course->title }}</h1>
                    <div class="prose prose-invert max-w-none text-gray-300 mb-6">
                        {{ $course->description }}
                    </div>
                    
                    <div class="flex flex-wrap gap-4 mb-8">
                        <div class="bg-gray-700/50 px-4 py-2 rounded-lg border border-gray-600">
                            <span class="text-gray-400 text-xs uppercase block">Rewards</span>
                            <div class="flex gap-3 mt-1">
                                @if($course->xp_reward)
                                    <span class="text-green-400 font-bold"><i class="fas fa-star"></i> {{ $course->xp_reward }} XP</span>
                                @endif
                                @if($course->coin_reward)
                                    <span class="text-yellow-400 font-bold"><i class="fas fa-coins"></i> {{ $course->coin_reward }} Coins</span>
                                @endif
                            </div>
                        </div>
                        <div class="bg-gray-700/50 px-4 py-2 rounded-lg border border-gray-600">
                            <span class="text-gray-400 text-xs uppercase block">Content</span>
                            <span class="text-white font-bold mt-1"><i class="fas fa-list"></i> {{ $course->chapters->count() }} Chapters</span>
                        </div>
                    </div>

                    @auth
                        @if($userCourse)
                            @if($userCourse->is_completed)
                                <div class="bg-green-500/20 border border-green-500 text-green-400 p-4 rounded-lg flex items-center gap-3 mb-4">
                                    <i class="fas fa-check-circle text-2xl"></i>
                                    <div>
                                        <h4 class="font-bold">Course Completed!</h4>
                                        <p class="text-sm">You have finished this course and claimed your rewards.</p>
                                    </div>
                                </div>
                                <a href="{{ route('courses.chapter', [$course->slug, $course->chapters->first()->id]) }}" class="inline-block bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-8 rounded-lg transition-colors">
                                    Review Course
                                </a>
                            @else
                                <div class="mb-4">
                                    <div class="flex justify-between text-sm text-gray-400 mb-1">
                                        <span>Progress</span>
                                        <span>{{ round(($course->chapters->where('order', '<', $userCourse->currentChapter->order ?? 0)->count() / $course->chapters->count()) * 100) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-700 rounded-full h-2.5">
                                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ ($course->chapters->where('order', '<', $userCourse->currentChapter->order ?? 0)->count() / $course->chapters->count()) * 100 }}%"></div>
                                    </div>
                                </div>
                                <a href="{{ route('courses.chapter', [$course->slug, $userCourse->current_chapter_id ?? $course->chapters->first()->id]) }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition-colors shadow-lg shadow-blue-600/20">
                                    Continue Learning
                                </a>
                            @endif
                        @else
                            <form action="{{ route('courses.start', $course->slug) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition-colors shadow-lg shadow-blue-600/20">
                                    Start Course
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition-colors">
                            Login to Start
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Sidebar / Chapter List -->
        <div class="lg:col-span-1">
            <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg border border-gray-700 sticky top-6">
                <div class="p-4 border-b border-gray-700 bg-gray-750">
                    <h3 class="font-bold text-white">Course Content</h3>
                </div>
                <div class="divide-y divide-gray-700 max-h-[calc(100vh-200px)] overflow-y-auto">
                    @foreach($course->chapters as $chapter)
                    <div class="p-4 flex items-center gap-3 {{ $userCourse && $userCourse->current_chapter_id == $chapter->id ? 'bg-blue-600/10 border-l-4 border-blue-500' : 'hover:bg-gray-750' }}">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                            {{ $userCourse && ($userCourse->is_completed || ($userCourse->currentChapter && $chapter->order < $userCourse->currentChapter->order)) 
                                ? 'bg-green-500 text-white' 
                                : 'bg-gray-700 text-gray-400' }}">
                            @if($userCourse && ($userCourse->is_completed || ($userCourse->currentChapter && $chapter->order < $userCourse->currentChapter->order)))
                                <i class="fas fa-check"></i>
                            @else
                                {{ $loop->iteration }}
                            @endif
                        </div>
                        <div class="flex-1">
                            <h4 class="text-gray-200 text-sm font-medium">{{ $chapter->title }}</h4>
                        </div>
                        @if($chapter->video_url)
                            <i class="fas fa-play-circle text-gray-500 text-xs"></i>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
