@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Bank Card -->
        <div class="lg:col-span-1">
            <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl shadow-xl border border-gray-700 p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-yellow-500/10 rounded-full blur-2xl"></div>
                
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h2 class="text-gray-400 text-sm font-bold uppercase tracking-wider">Saldo Bank</h2>
                        <div class="text-4xl font-bold text-white mt-2 flex items-center gap-2">
                            <i class="fas fa-coins text-yellow-500"></i>
                            {{ number_format($bankAccount->balance) }}
                        </div>
                    </div>
                    <div class="bg-gray-700/50 p-2 rounded-lg">
                        <i class="fas fa-university text-2xl text-blue-400"></i>
                    </div>
                </div>

                <div class="mb-8">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-400">Bunga Harian</span>
                        <span class="text-green-400 font-bold">{{ $bankAccount->interest_rate * 100 }}%</span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $bankAccount->interest_rate * 100 }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Bunga dihitung setiap 24 jam dari saldo tersimpan.</p>
                </div>

                <div class="space-y-4">
                    <!-- Deposit Form -->
                    <form action="{{ route('bank.deposit') }}" method="POST" class="bg-gray-800/50 p-4 rounded-lg border border-gray-700/50">
                        @csrf
                        <label class="block text-gray-400 text-xs font-bold mb-2 uppercase">Deposit</label>
                        <div class="flex gap-2">
                            <input type="number" name="amount" min="100" placeholder="Min 100" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white text-sm focus:outline-none focus:border-green-500">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-bold transition-colors">
                                <i class="fas fa-arrow-down"></i>
                            </button>
                        </div>
                        <div class="text-right mt-1">
                            <span class="text-xs text-gray-500">Dompet: {{ number_format($user->stats->coins) }}</span>
                        </div>
                    </form>

                    <!-- Withdraw Form -->
                    <form action="{{ route('bank.withdraw') }}" method="POST" class="bg-gray-800/50 p-4 rounded-lg border border-gray-700/50">
                        @csrf
                        <label class="block text-gray-400 text-xs font-bold mb-2 uppercase">Tarik Tunai</label>
                        <div class="flex gap-2">
                            <input type="number" name="amount" min="100" placeholder="Min 100" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white text-sm focus:outline-none focus:border-red-500">
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-bold transition-colors">
                                <i class="fas fa-arrow-up"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Transaction History -->
        <div class="lg:col-span-2">
            <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-700">
                    <h2 class="text-xl font-bold text-white">Riwayat Transaksi</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-700/50 text-gray-400 text-sm uppercase">
                                <th class="px-6 py-3">Waktu</th>
                                <th class="px-6 py-3">Tipe</th>
                                <th class="px-6 py-3">Keterangan</th>
                                <th class="px-6 py-3 text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @forelse($transactions as $trx)
                                <tr class="hover:bg-gray-700/30 transition-colors">
                                    <td class="px-6 py-4 text-gray-400 text-sm">
                                        {{ $trx->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($trx->type == 'bank_deposit')
                                            <span class="px-2 py-1 text-xs font-bold rounded bg-green-500/20 text-green-400">DEPOSIT</span>
                                        @elseif($trx->type == 'bank_withdraw')
                                            <span class="px-2 py-1 text-xs font-bold rounded bg-red-500/20 text-red-400">WITHDRAW</span>
                                        @elseif($trx->type == 'interest')
                                            <span class="px-2 py-1 text-xs font-bold rounded bg-blue-500/20 text-blue-400">BUNGA</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-300">{{ $trx->description }}</td>
                                    <td class="px-6 py-4 text-right font-bold {{ $trx->amount > 0 ? 'text-green-400' : 'text-red-400' }}">
                                        {{ $trx->amount > 0 ? '+' : '' }}{{ number_format($trx->amount) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                        Belum ada transaksi bank.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-700">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
