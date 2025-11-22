@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-white">Trading System</h1>
        <a href="{{ route('trades.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-colors flex items-center gap-2">
            <i class="fas fa-exchange-alt"></i> Buat Trade Baru
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500 text-green-500 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500/10 border border-red-500 text-red-500 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-700/50 text-gray-400 text-sm uppercase">
                        <th class="px-6 py-3">Partner</th>
                        <th class="px-6 py-3">Item Diberikan</th>
                        <th class="px-6 py-3">Koin Diberikan</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Waktu</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($trades as $trade)
                        @php
                            $isSender = $trade->sender_id === Auth::id();
                            $partner = $isSender ? $trade->receiver : $trade->sender;
                        @endphp
                        <tr class="hover:bg-gray-700/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $partner->avatar_url ?? 'https://ui-avatars.com/api/?name='.$partner->username }}" class="w-8 h-8 rounded-full">
                                    <div>
                                        <div class="font-bold text-white">{{ $partner->username }}</div>
                                        <div class="text-xs text-gray-500">{{ $isSender ? 'Outgoing' : 'Incoming' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($trade->sender_items)
                                    <div class="flex -space-x-2">
                                        @foreach($trade->sender_items as $item)
                                            @php $itemModel = \App\Models\Item::find($item['item_id']); @endphp
                                            @if($itemModel)
                                                <div class="w-8 h-8 rounded-full bg-gray-600 border-2 border-gray-800 flex items-center justify-center relative group" title="{{ $itemModel->name }} (x{{ $item['quantity'] }})">
                                                    @if($itemModel->image)
                                                        <img src="{{ $itemModel->image }}" class="w-full h-full object-contain rounded-full">
                                                    @else
                                                        <i class="fas fa-cube text-xs text-gray-400"></i>
                                                    @endif
                                                    <span class="absolute -top-1 -right-1 bg-blue-600 text-[10px] text-white px-1 rounded-full">{{ $item['quantity'] }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($trade->sender_coins > 0)
                                    <span class="text-yellow-500 font-bold flex items-center gap-1">
                                        <i class="fas fa-coins text-xs"></i> {{ number_format($trade->sender_coins) }}
                                    </span>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($trade->status == 'pending')
                                    <span class="px-2 py-1 text-xs font-bold rounded bg-yellow-500/20 text-yellow-400">PENDING</span>
                                @elseif($trade->status == 'accepted')
                                    <span class="px-2 py-1 text-xs font-bold rounded bg-green-500/20 text-green-400">ACCEPTED</span>
                                @elseif($trade->status == 'rejected')
                                    <span class="px-2 py-1 text-xs font-bold rounded bg-red-500/20 text-red-400">REJECTED</span>
                                @elseif($trade->status == 'cancelled')
                                    <span class="px-2 py-1 text-xs font-bold rounded bg-gray-500/20 text-gray-400">CANCELLED</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-400 text-sm">
                                {{ $trade->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($trade->status == 'pending')
                                    @if(!$isSender)
                                        <div class="flex justify-end gap-2">
                                            <form action="{{ route('trades.accept', $trade->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white p-2 rounded transition-colors" title="Terima">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('trades.reject', $trade->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white p-2 rounded transition-colors" title="Tolak">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <form action="{{ route('trades.cancel', $trade->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                                Batalkan
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Belum ada riwayat trade.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-700">
            {{ $trades->links() }}
        </div>
    </div>
</div>
@endsection
