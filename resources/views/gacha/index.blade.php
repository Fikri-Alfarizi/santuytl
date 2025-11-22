@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-white mb-4">Mystic Gacha</h1>
        <p class="text-gray-400 text-lg">Uji keberuntunganmu dan dapatkan item langka!</p>
    </div>

    @if(session('success'))
        <div class="max-w-2xl mx-auto bg-gradient-to-r from-yellow-500/20 to-purple-500/20 border border-yellow-500 text-white px-6 py-4 rounded-lg mb-8 text-center animate-pulse">
            <h3 class="text-2xl font-bold mb-2">🎉 CONGRATULATIONS! 🎉</h3>
            <div class="text-lg">{!! Str::markdown(session('success')) !!}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-2xl mx-auto bg-red-500/10 border border-red-500 text-red-500 px-6 py-4 rounded-lg mb-8 text-center">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-6xl mx-auto">
        <!-- Gacha Machine -->
        <div class="bg-gray-800 rounded-2xl p-8 border border-gray-700 shadow-2xl relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-600/20 to-blue-600/20 opacity-50 group-hover:opacity-70 transition-opacity"></div>
            
            <div class="relative z-10 text-center">
                <div class="mb-8 relative inline-block">
                    <div class="absolute -inset-4 bg-purple-500/30 rounded-full blur-xl animate-pulse"></div>
                    <i class="fas fa-gem text-8xl text-purple-400 drop-shadow-[0_0_15px_rgba(168,85,247,0.5)]"></i>
                </div>

                <div class="bg-gray-900/80 rounded-xl p-6 mb-8 border border-gray-700 backdrop-blur">
                    <div class="text-gray-400 text-sm uppercase tracking-wider mb-2">Biaya per Spin</div>
                    <div class="text-3xl font-bold text-yellow-500 flex items-center justify-center gap-2">
                        <i class="fas fa-coins"></i> 500
                    </div>
                </div>

                <form action="{{ route('gacha.spin') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-500 hover:to-blue-500 text-white font-bold py-4 px-8 rounded-xl text-xl shadow-lg transform hover:scale-105 transition-all duration-200 flex items-center justify-center gap-3">
                        <i class="fas fa-magic"></i> SPIN SEKARANG
                    </button>
                </form>
                
                <div class="mt-4 text-gray-500 text-sm">
                    Saldo Anda: <span class="text-yellow-500 font-bold">{{ number_format($user->stats->coins) }}</span>
                </div>
            </div>
        </div>

        <!-- Reward Pool -->
        <div class="space-y-6">
            <h3 class="text-2xl font-bold text-white flex items-center gap-2">
                <i class="fas fa-list"></i> Reward Pool
            </h3>
            
            <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
                <div class="max-h-[500px] overflow-y-auto custom-scrollbar">
                    @foreach($items as $item)
                        <div class="p-4 border-b border-gray-700 flex items-center gap-4 hover:bg-gray-700/50 transition-colors">
                            <div class="w-16 h-16 bg-gray-900 rounded-lg flex items-center justify-center border border-gray-600 relative overflow-hidden">
                                @if($item->image)
                                    <img src="{{ $item->image }}" class="w-full h-full object-contain">
                                @else
                                    <i class="fas fa-cube text-2xl text-gray-600"></i>
                                @endif
                                
                                <!-- Rarity Glow -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 right-0 h-1 
                                    @if($item->rarity == 'common') bg-gray-400
                                    @elseif($item->rarity == 'rare') bg-blue-400
                                    @elseif($item->rarity == 'epic') bg-purple-400
                                    @elseif($item->rarity == 'legendary') bg-yellow-400
                                    @elseif($item->rarity == 'mythic') bg-red-500
                                    @endif">
                                </div>
                            </div>
                            
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <h4 class="font-bold text-white">{{ $item->name }}</h4>
                                    <span class="text-xs font-bold px-2 py-1 rounded uppercase
                                        @if($item->rarity == 'common') bg-gray-600 text-gray-200
                                        @elseif($item->rarity == 'rare') bg-blue-900 text-blue-200
                                        @elseif($item->rarity == 'epic') bg-purple-900 text-purple-200
                                        @elseif($item->rarity == 'legendary') bg-yellow-900 text-yellow-200
                                        @elseif($item->rarity == 'mythic') bg-red-900 text-red-200
                                        @endif">
                                        {{ $item->rarity }}
                                    </span>
                                </div>
                                <p class="text-gray-400 text-xs mt-1">{{ Str::limit($item->description, 50) }}</p>
                                <div class="text-gray-500 text-xs mt-2">Chance: {{ $item->gacha_chance }}%</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
