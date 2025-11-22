@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <a href="{{ route('teams.index') }}" class="text-gray-400 hover:text-white transition-colors flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Back to Teams
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Team Info -->
        <div class="lg:col-span-1">
            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 shadow-lg text-center">
                <div class="w-32 h-32 mx-auto rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-5xl mb-6 shadow-xl">
                    {{ substr($team->name, 0, 1) }}
                </div>
                
                <h1 class="text-3xl font-bold text-white mb-2">{{ $team->name }}</h1>
                <p class="text-gray-400 mb-6">{{ $team->description ?? 'No description available.' }}</p>
                
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="bg-gray-700/50 p-3 rounded-lg">
                        <div class="text-xs text-gray-400 uppercase">Members</div>
                        <div class="text-xl font-bold text-white">{{ $team->members->count() }}</div>
                    </div>
                    <div class="bg-gray-700/50 p-3 rounded-lg">
                        <div class="text-xs text-gray-400 uppercase">Total XP</div>
                        <div class="text-xl font-bold text-blue-400">{{ number_format($team->total_xp) }}</div>
                    </div>
                </div>

                @auth
                    @if($team->members->contains(Auth::user()))
                        <form action="{{ route('teams.leave', $team) }}" method="POST" onsubmit="return confirm('Are you sure you want to leave this team?');">
                            @csrf
                            <button type="submit" class="w-full bg-red-600/20 hover:bg-red-600/40 text-red-500 border border-red-600 font-bold py-2 rounded-lg transition-colors">
                                Leave Team
                            </button>
                        </form>
                    @else
                        <form action="{{ route('teams.join', $team) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 rounded-lg transition-colors shadow-lg">
                                Join Team
                            </button>
                        </form>
                    @endif
                @endauth
            </div>
        </div>

        <!-- Members List -->
        <div class="lg:col-span-2">
            <div class="bg-gray-800 rounded-xl border border-gray-700 shadow-lg overflow-hidden">
                <div class="p-6 border-b border-gray-700">
                    <h3 class="text-xl font-bold text-white">Team Members</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-gray-400">
                        <thead class="bg-gray-700/50 text-gray-300 uppercase text-xs">
                            <tr>
                                <th class="py-3 px-6">Member</th>
                                <th class="py-3 px-6">Role</th>
                                <th class="py-3 px-6 text-right">Joined</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach($team->members as $member)
                            <tr class="hover:bg-gray-700/30 transition-colors">
                                <td class="py-4 px-6 flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gray-600 flex items-center justify-center text-white font-bold text-xs">
                                        {{ substr($member->name, 0, 1) }}
                                    </div>
                                    <span class="text-white font-medium">{{ $member->name }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    @if($member->pivot->role === 'leader')
                                        <span class="text-yellow-500 font-bold text-xs uppercase border border-yellow-500/30 bg-yellow-500/10 px-2 py-1 rounded">Leader</span>
                                    @else
                                        <span class="text-gray-500 text-xs uppercase">Member</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right text-sm">
                                    {{ \Carbon\Carbon::parse($member->pivot->joined_at)->diffForHumans() }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
