@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-white mb-4">Game Feed</h1>
        <p class="text-gray-400 text-lg">Latest news from the gaming world (Powered by Steam).</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($news as $item)
        <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg border border-gray-700 hover:border-blue-500 transition-all group flex flex-col">
            <div class="p-6 flex-1">
                <div class="flex justify-between items-start mb-4">
                    <span class="bg-blue-600/20 text-blue-400 text-xs font-bold px-2 py-1 rounded uppercase">
                        {{ isset($item['appid']) && $item['appid'] == 730 ? 'CS2' : 'Dota 2' }}
                    </span>
                    <span class="text-gray-500 text-xs">
                        {{ \Carbon\Carbon::createFromTimestamp($item['date'])->diffForHumans() }}
                    </span>
                </div>
                
                <h3 class="text-xl font-bold text-white mb-3 group-hover:text-blue-400 transition-colors">
                    <a href="{{ $item['url'] }}" target="_blank">{{ $item['title'] }}</a>
                </h3>
                
                <div class="text-gray-400 text-sm mb-4 line-clamp-4 prose prose-invert prose-sm">
                    {!! strip_tags($item['contents']) !!}
                </div>
            </div>
            
            <div class="p-4 bg-gray-750 border-t border-gray-700">
                <a href="{{ $item['url'] }}" target="_blank" class="text-blue-400 hover:text-white text-sm font-bold flex items-center gap-2 transition-colors">
                    Read on Steam <i class="fas fa-external-link-alt"></i>
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 text-gray-500">
            <i class="fas fa-gamepad text-4xl mb-4"></i>
            <p>No news available at the moment.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
