@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-white mb-4">Leaderboard</h1>
        <p class="text-gray-400 text-lg">Top players and community legends.</p>
    </div>

    <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <div class="flex border-b border-gray-700">
            <button class="flex-1 py-4 text-center font-bold text-gray-400 hover:text-white hover:bg-gray-700 transition-colors active-tab" onclick="switchTab(event, 'xp')">
                Global XP
            </button>
            <button class="flex-1 py-4 text-center font-bold text-gray-400 hover:text-white hover:bg-gray-700 transition-colors" onclick="switchTab(event, 'coins')">
                Global Coins
            </button>
            <button class="flex-1 py-4 text-center font-bold text-gray-400 hover:text-white hover:bg-gray-700 transition-colors" onclick="switchTab(event, 'weekly')">
                Weekly XP
            </button>
        </div>

        <div class="p-6">
            <!-- Global XP Tab -->
            <div id="xp" class="tab-content block">
                <h3 class="text-2xl font-bold text-white mb-6">Top XP Holders</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-gray-400">
                        <thead class="bg-gray-700 text-gray-200 uppercase text-sm">
                            <tr>
                                <th class="py-3 px-4">Rank</th>
                                <th class="py-3 px-4">User</th>
                                <th class="py-3 px-4">Level</th>
                                <th class="py-3 px-4 text-right">Total XP</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach($xpLeaderboard as $index => $stat)
                            <tr class="hover:bg-gray-700/50 transition-colors">
                                <td class="py-3 px-4 font-bold text-white">#{{ $index + 1 }}</td>
                                <td class="py-3 px-4 flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gray-600 flex items-center justify-center text-white font-bold">
                                        {{ substr($stat->user->name, 0, 1) }}
                                    </div>
                                    <span class="text-white">{{ $stat->user->name }}</span>
                                </td>
                                <td class="py-3 px-4 text-yellow-500 font-bold">{{ $stat->level }}</td>
                                <td class="py-3 px-4 text-right font-mono text-blue-400">{{ number_format($stat->xp) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Global Coins Tab -->
            <div id="coins" class="tab-content hidden">
                <h3 class="text-2xl font-bold text-white mb-6">Richest Players</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-gray-400">
                        <thead class="bg-gray-700 text-gray-200 uppercase text-sm">
                            <tr>
                                <th class="py-3 px-4">Rank</th>
                                <th class="py-3 px-4">User</th>
                                <th class="py-3 px-4 text-right">Coins</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach($coinLeaderboard as $index => $stat)
                            <tr class="hover:bg-gray-700/50 transition-colors">
                                <td class="py-3 px-4 font-bold text-white">#{{ $index + 1 }}</td>
                                <td class="py-3 px-4 flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gray-600 flex items-center justify-center text-white font-bold">
                                        {{ substr($stat->user->name, 0, 1) }}
                                    </div>
                                    <span class="text-white">{{ $stat->user->name }}</span>
                                </td>
                                <td class="py-3 px-4 text-right font-mono text-yellow-400">{{ number_format($stat->coins) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Weekly XP Tab -->
            <div id="weekly" class="tab-content hidden">
                <h3 class="text-2xl font-bold text-white mb-6">Weekly Top Performers</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-gray-400">
                        <thead class="bg-gray-700 text-gray-200 uppercase text-sm">
                            <tr>
                                <th class="py-3 px-4">Rank</th>
                                <th class="py-3 px-4">User</th>
                                <th class="py-3 px-4 text-right">Weekly XP</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach($weeklyLeaderboard as $index => $stat)
                            <tr class="hover:bg-gray-700/50 transition-colors">
                                <td class="py-3 px-4 font-bold text-white">#{{ $index + 1 }}</td>
                                <td class="py-3 px-4 flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gray-600 flex items-center justify-center text-white font-bold">
                                        {{ substr($stat->user->name, 0, 1) }}
                                    </div>
                                    <span class="text-white">{{ $stat->user->name }}</span>
                                </td>
                                <td class="py-3 px-4 text-right font-mono text-green-400">{{ number_format($stat->weekly_xp) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function switchTab(event, tabId) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.add('hidden');
            el.classList.remove('block');
        });
        
        // Show selected tab content
        document.getElementById(tabId).classList.remove('hidden');
        document.getElementById(tabId).classList.add('block');
        
        // Reset tab styles
        document.querySelectorAll('button').forEach(btn => {
            btn.classList.remove('text-white', 'bg-gray-700');
            btn.classList.add('text-gray-400');
        });
        
        // Highlight active tab
        event.currentTarget.classList.remove('text-gray-400');
        event.currentTarget.classList.add('text-white', 'bg-gray-700');
    }
    
    // Initialize first tab active style
    document.querySelector('.active-tab').classList.add('text-white', 'bg-gray-700');
    document.querySelector('.active-tab').classList.remove('text-gray-400');
</script>
@endpush
@endsection
