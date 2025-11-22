@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('forum.category.show', $category->slug) }}" class="text-gray-400 hover:text-white transition-colors flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Back to {{ $category->name }}
            </a>
        </div>

        <div class="bg-gray-800 rounded-xl border border-gray-700 shadow-lg p-8">
            <h1 class="text-3xl font-bold text-white mb-6">Create New Thread</h1>
            
            <form action="{{ route('forum.thread.store', $category->slug) }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label for="title" class="block text-gray-300 font-bold mb-2">Title</label>
                    <input type="text" name="title" id="title" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-blue-500" placeholder="What's on your mind?" required>
                </div>

                <div class="mb-8">
                    <label for="content" class="block text-gray-300 font-bold mb-2">Content</label>
                    <textarea name="content" id="content" rows="10" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-blue-500" placeholder="Write your post content here..." required></textarea>
                    <p class="text-gray-500 text-sm mt-2">Markdown is supported.</p>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transform hover:scale-[1.02] transition-all">
                        Create Thread
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
