@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-white mb-4">Competitions</h1>
        <p class="text-gray-400 text-lg">Prove your worth in epic battles.</p>
    </div>

    <div class="grid grid-cols-1 gap-6">
        @forelse($competitions as $competition)
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 shadow-lg hover:border-blue-500/50 transition-all relative overflow-hidden">
            <div class="absolute top-0 right-0 bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-bl-lg uppercase">
                {{ $competition->status }}
            </div>
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-white mb-2">{{ $competition->title }}</h3>
                    <p class="text-gray-400 mb-4">{{ $competition->description }}</p>
                    
                    <div class="flex items-center gap-4 text-sm text-gray-500">
                        <span class="flex items-center gap-1">
                            <i class="fas fa-calendar"></i>
                            {{ $competition->start_date ? $competition->start_date->format('d M Y') : 'TBA' }}
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="fas fa-users"></i>
                            {{ ucfirst($competition->type) }}
                        </span>
                    </div>
                </div>
                
                <a href="{{ route('competitions.show', $competition) }}" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition-colors whitespace-nowrap">
                    View Details
                </a>
            </div>
        </div>
        @empty
        <div class="text-center py-12 text-gray-500 bg-gray-800 rounded-xl border border-gray-700">
            <i class="fas fa-trophy text-4xl mb-4"></i>
            <p>No active competitions at the moment.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $competitions->links() }}
    </div>
</div>
@endsection
