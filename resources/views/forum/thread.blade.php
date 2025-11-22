@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('forum.category.show', $category->slug) }}" class="text-gray-400 hover:text-white transition-colors flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Back to {{ $category->name }}
        </a>
    </div>

    <!-- Main Thread Post -->
    <div class="bg-gray-800 rounded-xl border border-gray-700 shadow-lg overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-700 bg-gray-750 flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">
                    @if($thread->is_pinned) <i class="fas fa-thumbtack text-yellow-500 mr-2"></i> @endif
                    @if($thread->is_locked) <i class="fas fa-lock text-red-500 mr-2"></i> @endif
                    {{ $thread->title }}
                </h1>
                <div class="text-sm text-gray-500">
                    Posted {{ $thread->created_at->format('d M Y, H:i') }}
                </div>
            </div>
            @if(Auth::id() === $thread->user_id || Auth::user()?->isAdmin())
                <!-- Admin/Owner controls could go here -->
            @endif
        </div>
        
        <div class="flex flex-col md:flex-row">
            <!-- User Info Sidebar -->
            <div class="md:w-64 bg-gray-800/50 p-6 border-r border-gray-700 flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-full bg-gray-600 flex items-center justify-center text-white font-bold text-2xl mb-4">
                    {{ substr($thread->user->name, 0, 1) }}
                </div>
                <h3 class="text-white font-bold mb-1">{{ $thread->user->name }}</h3>
                <span class="text-xs text-gray-500 uppercase mb-4">Thread Starter</span>
                
                <!-- User Badges/Stats could go here -->
            </div>
            
            <!-- Content -->
            <div class="flex-1 p-6">
                <div class="prose prose-invert max-w-none text-gray-300 mb-8">
                    {!! nl2br(e($thread->content)) !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Replies -->
    <div class="space-y-6 mb-8">
        @foreach($thread->posts as $post)
        <div class="bg-gray-800 rounded-xl border border-gray-700 shadow-lg overflow-hidden">
            <div class="flex flex-col md:flex-row">
                <div class="md:w-64 bg-gray-800/50 p-6 border-r border-gray-700 flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-full bg-gray-600 flex items-center justify-center text-white font-bold text-xl mb-3">
                        {{ substr($post->user->name, 0, 1) }}
                    </div>
                    <h3 class="text-white font-bold text-sm mb-1">{{ $post->user->name }}</h3>
                    <span class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</span>
                </div>
                <div class="flex-1 p-6">
                    <div class="prose prose-invert max-w-none text-gray-300">
                        {!! nl2br(e($post->content)) !!}
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Reply Form -->
    @auth
        @if(!$thread->is_locked)
        <div class="bg-gray-800 rounded-xl border border-gray-700 shadow-lg p-6">
            <h3 class="text-xl font-bold text-white mb-4">Post a Reply</h3>
            <form action="{{ route('forum.post.store', $thread->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <textarea name="content" rows="5" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-blue-500" placeholder="Write your reply here..." required></textarea>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">
                    Post Reply
                </button>
            </form>
        </div>
        @else
        <div class="bg-red-500/10 border border-red-500 text-red-400 p-4 rounded-lg text-center font-bold">
            <i class="fas fa-lock mr-2"></i> This thread is locked.
        </div>
        @endif
    @else
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-8 text-center">
            <p class="text-gray-400 mb-4">Please login to reply to this thread.</p>
            <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">
                Login
            </a>
        </div>
    @endauth
</div>
@endsection
