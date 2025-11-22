@extends('layouts.app')

@section('title', 'Our Staff')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-2xl font-bold mb-6">Discord Staff Members</h3>

                    @if (isset($error))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ $error }}</span>
                        </div>
                    @endif

                    @if ($staffMembers->isEmpty())
                        <p class="text-gray-600">No staff members found or bot is offline.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($staffMembers as $member)
                                <div class="bg-gray-100 rounded-lg shadow-md p-6 flex flex-col items-center text-center">
                                    <img src="{{ $member['avatar'] ?? 'https://discord.com/assets/f7e02ca35a7201c16f06.png' }}" alt="{{ $member['username'] }}" class="w-24 h-24 rounded-full object-cover mb-4 border-4 border-blue-500">
                                    <h4 class="text-xl font-semibold text-gray-900 mb-1">{{ $member['display_name'] ?? $member['username'] }}</h4>
                                    <p class="text-gray-700 text-sm mb-2">#{{ $member['discriminator'] }}</p>
                                    <div class="flex flex-wrap justify-center gap-2">
                                        @foreach ($member['roles'] as $role)
                                            <span class="px-3 py-1 text-xs font-medium rounded-full" style="background-color: #{{ $role['color'] == '0' ? '607d8b' : $role['color'] }}; color: white;">
                                                {{ $role['name'] }}
                                            </span>
                                        @endforeach
                                    </div>
                                    @if ($member['joined_at'])
                                        <p class="text-gray-500 text-xs mt-3">Joined Discord: {{ \Carbon\Carbon::parse($member['joined_at'])->format('M d, Y') }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection