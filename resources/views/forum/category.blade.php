@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <a href="{{ route('forum.index') }}" class="text-gray-400 hover:text-white transition-colors flex items-center gap-2 mb-2">
                <i class="fas fa-arrow-left"></i> Back to Forum
            </a>
            <h1 class="text-3xl font-bold text-white">{{ $category->name }}</h1>
            <p class="text-gray-400">{{ $category->description }}</p>
        </div>
        @auth
        <a href="{{ route('forum.thread.create', $category->slug) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-colors shadow-lg">
            New Thread
        </a>
        @endauth
    </div>

    <div class="bg-gray-800 rounded-xl border border-gray-700 shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-gray-400">
                <thead class="bg-gray-700/50 text-gray-300 uppercase text-xs">
                    <tr>
                        <th class="py-3 px-6 w-1/2">Topic</th>
                        <th class="py-3 px-6 text-center">Replies</th>
                        <th class="py-3 px-6 text-center">Views</th>
                        <th class="py-3 px-6 text-right">Last Activity</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($threads as $thread)
                    <tr class="hover:bg-gray-700/30 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-start gap-3">
                                <div class="mt-1">
                                    @if($thread->is_pinned)
                                        <i class="fas fa-thumbtack text-yellow-500" title="Pinned"></i>
                                    @elseif($thread->is_locked)
                                        <i class="fas fa-lock text-red-500" title="Locked"></i>
                                    @else
                                        <i class="fas fa-comment-alt text-blue-500"></i>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('forum.thread.show', [$category->slug, $thread->slug]) }}" class="text-white font-bold hover:text-blue-400 transition-colors block mb-1">
                                        {{ $thread->title }}
                                    </a>
                                    <div class="text-xs text-gray-500">
                                        Started by <span class="text-gray-300">{{ $thread->user->name }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-center font-mono">{{ $thread->posts_count }}</td>
                        <td class="py-4 px-6 text-center font-mono">{{ $thread->views_count }}</td>
                        <td class="py-4 px-6 text-right text-sm">
                            {{ $thread->created_at->diffForHumans() }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-4 block"></i>
                            No threads found in this category.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $threads->links() }}
    </div>
</div>
@endsection
