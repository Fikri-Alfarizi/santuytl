@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-white mb-4">Server Analytics</h1>
        <p class="text-gray-400 text-lg">Community growth and activity insights.</p>
    </div>

    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 shadow-lg">
            <div class="text-gray-400 text-sm uppercase mb-2">Total Members</div>
            <div class="text-4xl font-bold text-white">{{ number_format($totalUsers) }}</div>
            <div class="text-green-400 text-sm mt-2">
                <i class="fas fa-arrow-up"></i> {{ $newUsersToday }} new today
            </div>
        </div>
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 shadow-lg">
            <div class="text-gray-400 text-sm uppercase mb-2">Active Today</div>
            <div class="text-4xl font-bold text-blue-400">{{ number_format($activeToday) }}</div>
            <div class="text-gray-500 text-sm mt-2">
                Users active in last 24h
            </div>
        </div>
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 shadow-lg">
            <div class="text-gray-400 text-sm uppercase mb-2">Server Health</div>
            <div class="text-4xl font-bold text-green-400">Excellent</div>
            <div class="text-gray-500 text-sm mt-2">
                Based on activity metrics
            </div>
        </div>
    </div>

    <!-- Charts (Placeholder / Simple Visuals) -->
    <div class="bg-gray-800 rounded-xl p-8 border border-gray-700 shadow-lg mb-8">
        <h3 class="text-xl font-bold text-white mb-6">Activity Trend (Last 30 Days)</h3>
        
        @if($dailyStats->count() > 0)
            <div class="flex items-end space-x-2 h-64">
                @foreach($dailyStats as $stat)
                    <div class="flex-1 bg-blue-600/20 hover:bg-blue-600/40 transition-colors rounded-t relative group" style="height: {{ ($stat->active_users / max($dailyStats->max('active_users'), 1)) * 100 }}%">
                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block bg-gray-900 text-white text-xs p-2 rounded whitespace-nowrap z-10">
                            {{ $stat->date->format('d M') }}: {{ $stat->active_users }} Users
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-between text-xs text-gray-500 mt-2">
                <span>{{ $dailyStats->first()->date->format('d M') }}</span>
                <span>{{ $dailyStats->last()->date->format('d M') }}</span>
            </div>
        @else
            <div class="text-center text-gray-500 py-12">
                Not enough data to display chart.
            </div>
        @endif
    </div>
</div>
@endsection