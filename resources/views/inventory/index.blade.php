@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-white">Inventory Saya</h1>
        <div class="text-gray-400">
            Total Item: {{ $inventory->count() }}
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500 text-green-500 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if($inventory->isEmpty())
        <div class="bg-gray-800 rounded-lg p-8 text-center">
            <i class="fas fa-box-open text-6xl text-gray-600 mb-4"></i>
            <p class="text-gray-400 text-lg">Inventory Anda kosong.</p>
            <a href="{{ route('market.index') }}" class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">
                Ke Market
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($inventory as $inv)
                <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg border border-gray-700 hover:border-gray-600 transition-all">
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-gray-700 text-gray-300">
                                {{ strtoupper(str_replace('_', ' ', $inv->item->type)) }}
                            </span>
                            @if($inv->is_equipped)
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-green-500/20 text-green-400 border border-green-500/30">
                                    EQUIPPED
                                </span>
                            @endif
                        </div>

                        <div class="flex justify-center mb-4">
                            @if($inv->item->image)
                                <img src="{{ $inv->item->image }}" alt="{{ $inv->item->name }}" class="h-24 w-24 object-contain">
                            @else
                                <div class="h-24 w-24 bg-gray-700 rounded-full flex items-center justify-center">
                                    <i class="fas fa-cube text-4xl text-gray-500"></i>
                                </div>
                            @endif
                        </div>

                        <h3 class="text-xl font-bold text-white mb-2">{{ $inv->item->name }}</h3>
                        <p class="text-gray-400 text-sm mb-4 h-10 overflow-hidden">{{ Str::limit($inv->item->description, 60) }}</p>

                        <div class="flex justify-between items-center text-sm text-gray-500 mb-4">
                            <span>Qty: {{ $inv->quantity }}</span>
                            <span>Diperoleh: {{ $inv->created_at->diffForHumans() }}</span>
                        </div>

                        <div class="flex gap-2">
                            @if(in_array($inv->item->type, ['avatar_frame', 'profile_theme']))
                                @if(!$inv->is_equipped)
                                    <form action="{{ route('inventory.equip') }}" method="POST" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="inventory_id" value="{{ $inv->id }}">
                                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors">
                                            Gunakan
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="flex-1 bg-gray-700 text-gray-400 font-bold py-2 px-4 rounded cursor-not-allowed">
                                        Digunakan
                                    </button>
                                @endif
                            @endif
                            
                            @if($inv->item->is_tradeable)
                                <a href="{{ route('market.sell', ['inventory_id' => $inv->id]) }}" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-center transition-colors">
                                    Jual
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
