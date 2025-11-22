@extends('layouts.app')

@section('title', 'Job Selection')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-white mb-4">Pilih Job / Class</h1>
            <p class="text-gray-400 text-lg">Pilih spesialisasi Anda dan dapatkan bonus XP sesuai gaya bermain Anda.</p>
        </div>

        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500 text-green-500 px-4 py-3 rounded-xl mb-6 text-center">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500/10 border border-red-500 text-red-500 px-4 py-3 rounded-xl mb-6 text-center">
                {{ session('error') }}
            </div>
        @endif

        <!-- Current Job -->
        @if($currentJob)
            <div class="bg-gradient-to-r from-blue-900/50 to-purple-900/50 rounded-2xl p-8 mb-12 border border-blue-700/50 text-center relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-2xl font-bold text-white mb-2">Job Saat Ini</h2>
                    <div class="text-4xl font-bold text-blue-400 mb-4 flex items-center justify-center gap-3">
                        @if($currentJob->icon) <i class="{{ $currentJob->icon }}"></i> @endif
                        {{ $currentJob->name }}
                    </div>
                    <p class="text-gray-300 max-w-2xl mx-auto mb-4">{{ $currentJob->description }}</p>
                    <div class="inline-block bg-blue-500/20 text-blue-300 px-4 py-2 rounded-full text-sm font-semibold border border-blue-500/30">
                        Passive: {{ $currentJob->passive_skill_name }} (+{{ $currentJob->bonus_percentage }}% Bonus)
                    </div>
                </div>
            </div>
        @endif

        <!-- Job Selection Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($jobs as $job)
                <div class="bg-gray-800 rounded-2xl border {{ $currentJob && $currentJob->id == $job->id ? 'border-blue-500 ring-2 ring-blue-500/50' : 'border-gray-700 hover:border-gray-600' }} overflow-hidden transition-all duration-300 hover:transform hover:-translate-y-2 group h-full flex flex-col">
                    <!-- Job Header -->
                    <div class="p-6 text-center bg-gray-900/50 border-b border-gray-700">
                        <div class="w-20 h-20 mx-auto bg-gray-800 rounded-full flex items-center justify-center mb-4 text-4xl text-{{ $job->color ?? 'blue' }}-400 group-hover:scale-110 transition-transform shadow-lg">
                            <i class="{{ $job->icon ?? 'fas fa-user' }}"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-1">{{ $job->name }}</h3>
                        <div class="text-xs font-bold uppercase tracking-wider text-gray-500">Class</div>
                    </div>

                    <!-- Job Body -->
                    <div class="p-6 flex-grow">
                        <p class="text-gray-400 mb-6 text-center text-sm min-h-[60px]">{{ $job->description }}</p>
                        
                        <div class="bg-gray-900/50 rounded-xl p-4 mb-4 border border-gray-700/50">
                            <div class="text-xs text-gray-500 uppercase font-bold mb-2">Passive Skill</div>
                            <div class="font-bold text-white mb-1">{{ $job->passive_skill_name }}</div>
                            <p class="text-xs text-gray-400">{{ $job->passive_skill_description }}</p>
                        </div>

                        <div class="flex items-center justify-center gap-2 text-sm text-green-400 font-semibold">
                            <i class="fas fa-arrow-up"></i> Bonus {{ $job->bonus_percentage }}% XP
                        </div>
                    </div>

                    <!-- Job Footer / Action -->
                    <div class="p-6 bg-gray-900/30 border-t border-gray-700 mt-auto">
                        @if($currentJob && $currentJob->id == $job->id)
                            <button disabled class="w-full bg-blue-600/50 text-white font-bold py-3 px-4 rounded-xl cursor-default">
                                <i class="fas fa-check mr-2"></i> Terpasang
                            </button>
                        @else
                            <form action="{{ $currentJob ? route('jobs.change') : route('jobs.select') }}" method="POST">
                                @csrf
                                <input type="hidden" name="job_id" value="{{ $job->id }}">
                                <button type="submit" class="w-full bg-gray-700 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-xl transition-colors"
                                    onclick="return confirm('{{ $currentJob ? 'Ganti job akan dikenakan biaya 500 koin. Lanjutkan?' : 'Apakah Anda yakin memilih job ini?' }}')">
                                    {{ $currentJob ? 'Ganti Job (500 Koin)' : 'Pilih Job Ini' }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
