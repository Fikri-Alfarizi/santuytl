@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('trades.index') }}" class="text-gray-400 hover:text-white transition-colors">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-3xl font-bold text-white">Buat Trade Baru</h1>
        </div>

        <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 p-6">
            <form action="{{ route('trades.store') }}" method="POST">
                @csrf
                
                <!-- Select Partner -->
                <div class="mb-8">
                    <label class="block text-gray-400 text-sm font-bold mb-2">Pilih Partner Trade</label>
                    <select name="receiver_id" class="w-full bg-gray-700 border border-gray-600 rounded-lg py-3 px-4 text-white focus:outline-none focus:border-blue-500" required>
                        <option value="">-- Pilih User --</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->username }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Coins -->
                    <div>
                        <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-coins text-yellow-500"></i> Koin
                        </h3>
                        <div class="bg-gray-700/50 p-4 rounded-lg border border-gray-600">
                            <label class="block text-gray-400 text-xs mb-2">Jumlah Koin Ditawarkan</label>
                            <input type="number" name="coins" min="0" placeholder="0" class="w-full bg-gray-800 border border-gray-600 rounded px-3 py-2 text-white focus:outline-none focus:border-yellow-500">
                            <div class="text-right mt-1">
                                <span class="text-xs text-gray-500">Saldo: {{ number_format(Auth::user()->stats->coins) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Items -->
                    <div>
                        <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-box-open text-blue-500"></i> Item
                        </h3>
                        <div class="bg-gray-700/50 p-4 rounded-lg border border-gray-600 max-h-80 overflow-y-auto custom-scrollbar">
                            @if($inventory->isEmpty())
                                <p class="text-gray-500 text-sm text-center py-4">Inventory kosong.</p>
                            @else
                                <div class="space-y-3">
                                    @foreach($inventory as $index => $inv)
                                        <div class="flex items-center gap-3 bg-gray-800 p-3 rounded border border-gray-700">
                                            <input type="checkbox" name="items[{{ $index }}][inventory_id]" value="{{ $inv->id }}" class="w-4 h-4 rounded border-gray-600 text-blue-600 focus:ring-blue-500 bg-gray-700">
                                            
                                            @if($inv->item->image)
                                                <img src="{{ $inv->item->image }}" class="w-10 h-10 object-contain">
                                            @else
                                                <div class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-cube text-gray-500"></i>
                                                </div>
                                            @endif
                                            
                                            <div class="flex-1">
                                                <div class="text-sm font-bold text-white">{{ $inv->item->name }}</div>
                                                <div class="text-xs text-gray-400">Miliki: {{ $inv->quantity }}</div>
                                            </div>

                                            <input type="number" name="items[{{ $index }}][quantity]" min="1" max="{{ $inv->quantity }}" value="1" class="w-16 bg-gray-900 border border-gray-600 rounded px-2 py-1 text-white text-sm text-center focus:outline-none focus:border-blue-500">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-700">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane"></i> Kirim Permintaan Trade
                    </button>
                    <p class="text-center text-gray-500 text-xs mt-4">
                        Pastikan Anda memeriksa kembali item dan koin yang akan dikirim. Transaksi tidak dapat dibatalkan setelah diterima.
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
