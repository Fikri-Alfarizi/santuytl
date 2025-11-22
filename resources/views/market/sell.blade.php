@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('market.index') }}" class="text-gray-400 hover:text-white transition-colors">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-3xl font-bold text-white">Jual Item</h1>
        </div>

        @if($inventory->isEmpty())
            <div class="bg-gray-800 rounded-lg p-8 text-center border border-gray-700">
                <i class="fas fa-box-open text-6xl text-gray-600 mb-4"></i>
                <p class="text-gray-400 text-lg mb-4">Anda tidak memiliki item yang bisa dijual.</p>
                <a href="{{ route('inventory.index') }}" class="text-blue-400 hover:text-blue-300">
                    Cek Inventory
                </a>
            </div>
        @else
            <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 p-6">
                <form action="{{ route('market.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <label class="block text-gray-400 text-sm font-bold mb-2">Pilih Item</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-60 overflow-y-auto custom-scrollbar p-2">
                            @foreach($inventory as $inv)
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="inventory_id" value="{{ $inv->id }}" class="peer sr-only" required>
                                    <div class="p-4 rounded-lg border border-gray-600 peer-checked:border-blue-500 peer-checked:bg-blue-500/10 hover:bg-gray-700 transition-all">
                                        <div class="flex items-center gap-3">
                                            @if($inv->item->image)
                                                <img src="{{ $inv->item->image }}" class="w-12 h-12 object-contain">
                                            @else
                                                <div class="w-12 h-12 bg-gray-600 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-cube text-gray-400"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-bold text-white">{{ $inv->item->name }}</div>
                                                <div class="text-xs text-gray-400">Qty: {{ $inv->quantity }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="absolute top-2 right-2 hidden peer-checked:block text-blue-500">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-gray-400 text-sm font-bold mb-2">Harga (Koin)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-coins text-yellow-500"></i>
                                </div>
                                <input type="number" name="price" min="1" class="w-full bg-gray-700 border border-gray-600 rounded-lg py-2 pl-10 pr-4 text-white focus:outline-none focus:border-blue-500" placeholder="0" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm font-bold mb-2">Jumlah</label>
                            <input type="number" name="quantity" min="1" value="1" class="w-full bg-gray-700 border border-gray-600 rounded-lg py-2 px-4 text-white focus:outline-none focus:border-blue-500" required>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition-colors">
                        Pasang Iklan
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
