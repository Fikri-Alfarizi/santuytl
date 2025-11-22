@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-4xl font-bold text-white mb-2">Teams</h1>
            <p class="text-gray-400">Join a team and compete for glory!</p>
        </div>
        <a href="{{ route('teams.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-colors shadow-lg">
            Create Team
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500/20 border border-red-500 text-red-400 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($teams as $team)
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 shadow-lg hover:border-gray-600 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl">
                    {{ substr($team->name, 0, 1) }}
                </div>
                <span class="bg-gray-700 text-gray-300 text-xs px-2 py-1 rounded-full">
                    {{ $team->members_count }} Members
                </span>
            </div>
            
            <h3 class="text-xl font-bold text-white mb-2">{{ $team->name }}</h3>
            <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ $team->description ?? 'No description provided.' }}</p>
            
            <div class="flex items-center justify-between text-sm text-gray-500 mb-6">
                <span>XP: {{ number_format($team->total_xp) }}</span>
                <span>Leader: {{ $team->leader->name ?? 'Unknown' }}</span>
            </div>
            
            <a href="{{ route('teams.show', $team) }}" class="block w-full bg-gray-700 hover:bg-gray-600 text-white text-center font-bold py-2 rounded-lg transition-colors">
                View Team
            </a>
        </div>
        @empty
        <div class="col-span-full text-center py-12 text-gray-500">
            <i class="fas fa-users text-4xl mb-4"></i>
            <p>No teams found. Be the first to create one!</p>
        </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $teams->links() }}
    </div>
</div>
@endsection
