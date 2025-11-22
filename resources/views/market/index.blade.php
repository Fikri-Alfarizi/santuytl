@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Marketplace</h1>
            <p class="text-gray-400">Beli item langka dari pemain lain</p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('market.sell') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition-colors flex items-center gap-2">
                <i class="fas fa-plus"></i> Jual Item
            </a>
            <div class="bg-gray-800 px-4 py-2 rounded-lg border border-gray-700 flex items-center gap-2">
                <i class="fas fa-coins text-yellow-500"></i>
                <span class="font-bold text-white">{{ number_format(Auth::user()->stats->coins) }}</span>
            </div>
        </div>
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

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($listings as $listing)
            <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg border border-gray-700 hover:border-gray-600 transition-all group">
                <div class="p-4">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <img src="{{ $listing->seller->avatar_url ?? 'https://ui-avatars.com/api/?name='.$listing->seller->username }}" class="w-6 h-6 rounded-full">
                            <span class="text-sm text-gray-400">{{ $listing->seller->username }}</span>
                        </div>
                        <span class="text-xs text-gray-500">{{ $listing->created_at->diffForHumans() }}</span>
                    </div>

                    <div class="flex justify-center mb-4 relative">
                        @if($listing->item->image)
                            <img src="{{ $listing->item->image }}" alt="{{ $listing->item->name }}" class="h-32 w-32 object-contain group-hover:scale-110 transition-transform">
                        @else
                            <div class="h-32 w-32 bg-gray-700 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-cube text-5xl text-gray-500"></i>
                            </div>
                        @endif
                        <div class="absolute top-0 right-0 bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded">
                            x{{ $listing->quantity }}
                        </div>
                    </div>

                    <h3 class="text-xl font-bold text-white mb-1">{{ $listing->item->name }}</h3>
                    <p class="text-gray-400 text-sm mb-4">{{ Str::limit($listing->item->description, 50) }}</p>

                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-700">
                        <div class="text-yellow-500 font-bold text-lg flex items-center gap-1">
                            <i class="fas fa-coins"></i>
                            {{ number_format($listing->price_coins) }}
                        </div>
                        
                        @if($listing->seller_id !== Auth::id())
                            <form action="{{ route('market.buy') }}" method="POST">
                                @csrf
                                <input type="hidden" name="listing_id" value="{{ $listing->id }}">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors text-sm">
                                    Beli Sekarang
                                </button>
                            </form>
                        @else
                            <span class="text-gray-500 text-sm italic">Item Anda</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <div class="bg-gray-800 rounded-lg p-8 inline-block">
                    <i class="fas fa-store-slash text-6xl text-gray-600 mb-4"></i>
                    <p class="text-gray-400 text-lg">Belum ada item di market saat ini.</p>
                    <a href="{{ route('market.sell') }}" class="mt-4 inline-block text-blue-400 hover:text-blue-300">
                        Jadilah yang pertama menjual item!
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $listings->links() }}
    </div>
</div>
@endsection
