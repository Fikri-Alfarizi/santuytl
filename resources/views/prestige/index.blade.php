@extends('layouts.app')

@section('title', 'Prestige System')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-white mb-4">Prestige System</h1>
            <p class="text-gray-400 text-lg">Reset level Anda untuk mendapatkan status legendaris dan bonus permanen.</p>
        </div>

        <!-- Current Status Card -->
        <div class="bg-gray-800 rounded-2xl p-8 mb-8 border border-gray-700 shadow-xl relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <i class="fas fa-crown text-9xl text-yellow-500"></i>
            </div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-white">Status Saat Ini</h2>
                        <p class="text-gray-400">Level & Prestige Progress</p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-400">Prestige Level</div>
                        <div class="text-3xl font-bold text-yellow-500">
                            <i class="fas fa-star mr-2"></i>{{ $stats->prestige_level }}
                        </div>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mb-6">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-white">Level {{ $stats->level }}</span>
                        <span class="text-gray-400">Target: Level {{ $maxLevel }}</span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-4 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-4 rounded-full transition-all duration-1000" 
                             style="width: {{ $progress }}%"></div>
                    </div>
                    <div class="text-right text-xs text-gray-500 mt-1">{{ number_format($progress, 1) }}% Menuju Prestige</div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
                    <div class="bg-gray-900/50 p-4 rounded-xl border border-gray-700">
                        <div class="text-gray-400 text-sm mb-1">Total XP</div>
                        <div class="text-xl font-bold text-white">{{ number_format($stats->xp) }}</div>
                    </div>
                    <div class="bg-gray-900/50 p-4 rounded-xl border border-gray-700">
                        <div class="text-gray-400 text-sm mb-1">Total Prestige</div>
                        <div class="text-xl font-bold text-white">{{ $stats->total_prestiges }}</div>
                    </div>
                    <div class="bg-gray-900/50 p-4 rounded-xl border border-gray-700">
                        <div class="text-gray-400 text-sm mb-1">Next Bonus</div>
                        <div class="text-xl font-bold text-green-400">+{{ ($stats->prestige_level + 1) * 10 }}% XP</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Requirements -->
            <div class="bg-gray-800 rounded-2xl p-6 border border-gray-700">
                <h3 class="text-xl font-bold text-white mb-4">Syarat Prestige</h3>
                <ul class="space-y-3">
                    <li class="flex items-center {{ $stats->level >= $maxLevel ? 'text-green-400' : 'text-red-400' }}">
                        <i class="fas {{ $stats->level >= $maxLevel ? 'fa-check-circle' : 'fa-times-circle' }} mr-3"></i>
                        Mencapai Level {{ $maxLevel }}
                    </li>
                    <li class="flex items-center text-green-400">
                        <i class="fas fa-check-circle mr-3"></i>
                        Tidak sedang dalam masa hukuman
                    </li>
                </ul>
            </div>

            <!-- Rewards & Button -->
            <div class="bg-gray-800 rounded-2xl p-6 border border-gray-700 flex flex-col justify-between">
                <div>
                    <h3 class="text-xl font-bold text-white mb-4">Hadiah Prestige</h3>
                    <ul class="space-y-2 text-gray-300 mb-6">
                        <li class="flex items-center"><i class="fas fa-gift text-purple-400 mr-2"></i> Reset Level ke 1</li>
                        <li class="flex items-center"><i class="fas fa-arrow-up text-green-400 mr-2"></i> +1 Prestige Level</li>
                        <li class="flex items-center"><i class="fas fa-gem text-blue-400 mr-2"></i> Badge Prestige Eksklusif</li>
                        <li class="flex items-center"><i class="fas fa-coins text-yellow-400 mr-2"></i> Bonus 1000 Koin</li>
                    </ul>
                </div>

                @if($canPrestige)
                    <form action="{{ route('prestige.do') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin melakukan Prestige? Level Anda akan direset ke 1.');">
                        @csrf
                        <button type="submit" class="w-full bg-gradient-to-r from-yellow-500 to-orange-600 hover:from-yellow-600 hover:to-orange-700 text-white font-bold py-3 px-6 rounded-xl transition-all transform hover:scale-105 shadow-lg">
                            <i class="fas fa-crown mr-2"></i> Lakukan Prestige Sekarang
                        </button>
                    </form>
                @else
                    <button disabled class="w-full bg-gray-700 text-gray-500 font-bold py-3 px-6 rounded-xl cursor-not-allowed">
                        <i class="fas fa-lock mr-2"></i> Belum Memenuhi Syarat
                    </button>
                @endif
            </div>
        </div>
        
        <!-- History Link -->
        <div class="text-center mt-8">
            <a href="{{ route('prestige.history') }}" class="text-blue-400 hover:text-blue-300 transition-colors">
                Lihat Riwayat Prestige <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
</div>
@endsection
