@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Profile Header -->
    <div class="bg-gray-800 rounded-xl border border-gray-700 shadow-lg overflow-hidden mb-8 relative">
        <!-- Cover Image (Placeholder) -->
        <div class="h-48 bg-gradient-to-r from-blue-900 to-purple-900"></div>
        
        <div class="px-8 pb-8">
            <div class="relative flex flex-col md:flex-row items-end -mt-16 mb-6">
                <!-- Avatar -->
                <div class="w-32 h-32 rounded-full border-4 border-gray-800 bg-gray-700 overflow-hidden shadow-xl z-10">
                    @if($user->avatar)
                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-4xl font-bold text-white bg-gradient-to-br from-blue-500 to-purple-600">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                
                <!-- Name & Badges -->
                <div class="md:ml-6 mb-4 md:mb-0 flex-1">
                    <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                        {{ $user->name }}
                        @if($userStats && $userStats->prestige_level > 0)
                            <span class="bg-yellow-500/20 text-yellow-500 text-xs px-2 py-1 rounded border border-yellow-500/50" title="Prestige Level">
                                P{{ $userStats->prestige_level }}
                            </span>
                        @endif
                    </h1>
                    <div class="text-gray-400 flex items-center gap-4 mt-1">
                        @if($team)
                            <span class="flex items-center gap-1 text-blue-400">
                                <i class="fas fa-users"></i> {{ $team->name }}
                            </span>
                        @endif
                        <span class="flex items-center gap-1">
                            <i class="fas fa-calendar-alt"></i> Joined {{ $user->created_at->format('M Y') }}
                        </span>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <!-- Add Friend / Message buttons could go here -->
                </div>
            </div>

            <!-- Stats Grid -->
            @if($userStats)
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-gray-700/50 p-4 rounded-lg text-center">
                    <div class="text-gray-400 text-xs uppercase mb-1">Level</div>
                    <div class="text-2xl font-bold text-white">{{ $userStats->level }}</div>
                </div>
                <div class="bg-gray-700/50 p-4 rounded-lg text-center">
                    <div class="text-gray-400 text-xs uppercase mb-1">XP</div>
                    <div class="text-2xl font-bold text-blue-400">{{ number_format($userStats->xp) }}</div>
                </div>
                <div class="bg-gray-700/50 p-4 rounded-lg text-center">
                    <div class="text-gray-400 text-xs uppercase mb-1">Reputation</div>
                    <div class="text-2xl font-bold text-green-400">{{ number_format($userStats->reputation) }}</div>
                </div>
                <div class="bg-gray-700/50 p-4 rounded-lg text-center">
                    <div class="text-gray-400 text-xs uppercase mb-1">Coins</div>
                    <div class="text-2xl font-bold text-yellow-400">{{ number_format($userStats->coins) }}</div>
                </div>
            </div>
            @endif

            <!-- Badges -->
            @if($userBadges->count() > 0)
            <div class="mb-8">
                <h3 class="text-xl font-bold text-white mb-4">Badges</h3>
                <div class="flex flex-wrap gap-4">
                    @foreach($userBadges as $userBadge)
                    <div class="bg-gray-700/50 px-4 py-2 rounded-full flex items-center gap-2 border border-gray-600" title="{{ $userBadge->badge->description }}">
                        @if($userBadge->badge->icon)
                            <img src="{{ $userBadge->badge->icon }}" class="w-6 h-6" alt="Badge Icon">
                        @else
                            <i class="fas fa-medal text-yellow-500"></i>
                        @endif
                        <span class="text-gray-200 font-medium">{{ $userBadge->badge->name }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            @endif
        </div>
    </div>

    <!-- History Section -->
    @if(isset($history) && $history->count() > 0)
    <div class="bg-gray-800 rounded-xl border border-gray-700 shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-700">
            <h3 class="text-xl font-bold text-white">Recent Activity</h3>
        </div>
        <div class="divide-y divide-gray-700">
            @foreach($history as $item)
            <div class="p-4 flex items-center gap-4 hover:bg-gray-750 transition-colors">
                <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-gray-400">
                    @if($item->type == 'level_up') <i class="fas fa-arrow-up text-green-400"></i>
                    @elseif($item->type == 'badge_earned') <i class="fas fa-medal text-yellow-400"></i>
                    @elseif($item->type == 'event_won') <i class="fas fa-trophy text-blue-400"></i>
                    @else <i class="fas fa-history"></i>
                    @endif
                </div>
                <div>
                    <p class="text-white font-medium">{{ $item->description }}</p>
                    <span class="text-xs text-gray-500">{{ $item->created_at->diffForHumans() }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
