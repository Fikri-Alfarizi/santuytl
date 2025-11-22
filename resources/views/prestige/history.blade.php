@extends('layouts.app')

@section('title', 'Riwayat Prestige')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-white">Riwayat Prestige</h1>
            <a href="{{ route('prestige.index') }}" class="text-gray-400 hover:text-white transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        <div class="bg-gray-800 rounded-2xl overflow-hidden border border-gray-700 shadow-xl">
            @if($history->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-900/50 text-gray-400 uppercase text-xs font-bold">
                            <tr>
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4">Prestige Level</th>
                                <th class="px-6 py-4">Level Sebelumnya</th>
                                <th class="px-6 py-4">XP Terakhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach($history as $item)
                                <tr class="hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 text-white">
                                        {{ $item->prestiged_at->format('d M Y, H:i') }}
                                        <div class="text-xs text-gray-500">{{ $item->prestiged_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-500/20 text-yellow-500 border border-yellow-500/30">
                                            <i class="fas fa-star mr-1"></i> Level {{ $item->prestige_level }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-300">Level {{ $item->old_level }}</td>
                                    <td class="px-6 py-4 text-gray-300">{{ number_format($item->old_xp) }} XP</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-700">
                    {{ $history->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="text-gray-600 text-6xl mb-4"><i class="fas fa-history"></i></div>
                    <h3 class="text-xl font-bold text-white mb-2">Belum Ada Riwayat</h3>
                    <p class="text-gray-400">Anda belum pernah melakukan prestige.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
