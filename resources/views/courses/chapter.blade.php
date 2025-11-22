@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('courses.show', $course->slug) }}" class="text-gray-400 hover:text-white transition-colors flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Back to Course Overview
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-3">
            <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg border border-gray-700 mb-8">
                @if($chapter->video_url)
                <div class="aspect-w-16 aspect-h-9 bg-black">
                    <iframe src="{{ $chapter->video_url }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-full"></iframe>
                </div>
                @endif
                
                <div class="p-8">
                    <h1 class="text-2xl font-bold text-white mb-6">{{ $chapter->title }}</h1>
                    
                    <div class="prose prose-invert max-w-none text-gray-300 mb-8">
                        {!! Str::markdown($chapter->content) !!}
                    </div>

                    <div class="flex justify-between items-center pt-8 border-t border-gray-700">
                        <div>
                            <!-- Previous button logic could go here -->
                        </div>
                        
                        <form action="{{ route('courses.chapter.complete', [$course->slug, $chapter->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition-colors flex items-center gap-2">
                                @if($nextChapter)
                                    Next Chapter <i class="fas fa-arrow-right"></i>
                                @else
                                    Finish Course <i class="fas fa-check-circle"></i>
                                @endif
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg border border-gray-700 sticky top-6">
                <div class="p-4 border-b border-gray-700 bg-gray-750">
                    <h3 class="font-bold text-white">Course Content</h3>
                </div>
                <div class="divide-y divide-gray-700 max-h-[calc(100vh-200px)] overflow-y-auto">
                    @foreach($course->chapters as $c)
                    <a href="{{ route('courses.chapter', [$course->slug, $c->id]) }}" class="block p-4 flex items-center gap-3 {{ $c->id == $chapter->id ? 'bg-blue-600/10 border-l-4 border-blue-500' : 'hover:bg-gray-750 transition-colors' }}">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                            {{ $userCourse && ($userCourse->is_completed || ($userCourse->currentChapter && $c->order < $userCourse->currentChapter->order)) 
                                ? 'bg-green-500 text-white' 
                                : ($c->id == $chapter->id ? 'bg-blue-500 text-white' : 'bg-gray-700 text-gray-400') }}">
                            @if($userCourse && ($userCourse->is_completed || ($userCourse->currentChapter && $c->order < $userCourse->currentChapter->order)))
                                <i class="fas fa-check"></i>
                            @else
                                {{ $loop->iteration }}
                            @endif
                        </div>
                        <div class="flex-1">
                            <h4 class="text-gray-200 text-sm font-medium {{ $c->id == $chapter->id ? 'text-blue-400' : '' }}">{{ $c->title }}</h4>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
