@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <a href="{{ route('competitions.index') }}" class="text-gray-400 hover:text-white transition-colors flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Back to Competitions
        </a>
    </div>

    <div class="bg-gray-800 rounded-xl overflow-hidden border border-gray-700 shadow-lg mb-8">
        <div class="p-8 text-center border-b border-gray-700 bg-gradient-to-b from-gray-800 to-gray-900">
            <span class="inline-block bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase mb-4">
                {{ $competition->status }}
            </span>
            <h1 class="text-4xl font-bold text-white mb-4">{{ $competition->title }}</h1>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">{{ $competition->description }}</p>
            
            <div class="flex justify-center gap-8 mt-8 text-sm">
                <div class="text-center">
                    <div class="text-gray-500 uppercase text-xs mb-1">Start Date</div>
                    <div class="text-white font-bold">{{ $competition->start_date ? $competition->start_date->format('d M Y H:i') : 'TBA' }}</div>
                </div>
                <div class="text-center">
                    <div class="text-gray-500 uppercase text-xs mb-1">End Date</div>
                    <div class="text-white font-bold">{{ $competition->end_date ? $competition->end_date->format('d M Y H:i') : 'TBA' }}</div>
                </div>
                <div class="text-center">
                    <div class="text-gray-500 uppercase text-xs mb-1">Type</div>
                    <div class="text-white font-bold">{{ ucfirst($competition->type) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaderboard / Participants -->
    <div class="bg-gray-800 rounded-xl border border-gray-700 shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-700">
            <h3 class="text-xl font-bold text-white">Standings</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-gray-400">
                <thead class="bg-gray-700/50 text-gray-300 uppercase text-xs">
                    <tr>
                        <th class="py-3 px-6">Rank</th>
                        <th class="py-3 px-6">Participant</th>
                        <th class="py-3 px-6 text-right">Score</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($competition->participants->sortByDesc('score') as $index => $participant)
                    <tr class="hover:bg-gray-700/30 transition-colors">
                        <td class="py-4 px-6 font-bold text-white">#{{ $index + 1 }}</td>
                        <td class="py-4 px-6">
                            @if($competition->type === 'team')
                                <span class="text-white font-bold">{{ $participant->team->name ?? 'Unknown Team' }}</span>
                            @else
                                <span class="text-white font-bold">{{ $participant->user->name ?? 'Unknown User' }}</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right font-mono text-yellow-400 font-bold">
                            {{ number_format($participant->score) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-8 text-center text-gray-500">
                            No participants yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
