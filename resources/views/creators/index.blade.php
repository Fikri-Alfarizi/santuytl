@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-white mb-4">Creator Program</h1>
        <p class="text-gray-400 text-lg">Join our community of content creators and get exclusive perks.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
        <!-- Benefits -->
        <div class="bg-gray-800 rounded-xl p-8 shadow-lg border border-gray-700">
            <h2 class="text-2xl font-bold text-white mb-6">Why Join?</h2>
            <ul class="space-y-4">
                <li class="flex items-start gap-3">
                    <div class="bg-purple-500/20 p-2 rounded-lg text-purple-400">
                        <i class="fas fa-check"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-white">Exclusive Badge</h4>
                        <p class="text-gray-400 text-sm">Get a unique "Creator" badge on your profile.</p>
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <div class="bg-blue-500/20 p-2 rounded-lg text-blue-400">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-white">Promotion</h4>
                        <p class="text-gray-400 text-sm">We'll feature your content on our community channels.</p>
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <div class="bg-green-500/20 p-2 rounded-lg text-green-400">
                        <i class="fas fa-gift"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-white">Special Rewards</h4>
                        <p class="text-gray-400 text-sm">Earn extra coins and XP for your contributions.</p>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Application Form -->
        <div class="bg-gray-800 rounded-xl p-8 shadow-lg border border-gray-700">
            <h2 class="text-2xl font-bold text-white mb-6">Apply Now</h2>
            
            @if(session('success'))
                <div class="bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-500/20 border border-red-500 text-red-400 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @auth
                @if($application)
                    <div class="text-center py-8">
                        <div class="inline-block p-4 rounded-full bg-gray-700 mb-4">
                            @if($application->status == 'pending')
                                <i class="fas fa-clock text-4xl text-yellow-400"></i>
                            @elseif($application->status == 'approved')
                                <i class="fas fa-check-circle text-4xl text-green-400"></i>
                            @else
                                <i class="fas fa-times-circle text-4xl text-red-400"></i>
                            @endif
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Application Status: {{ ucfirst($application->status) }}</h3>
                        <p class="text-gray-400">Submitted on {{ $application->created_at->format('M d, Y') }}</p>
                    </div>
                @else
                    <form action="{{ route('creators.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-400 text-sm font-bold mb-2" for="platform">
                                Platform
                            </label>
                            <select name="platform" id="platform" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white focus:outline-none focus:border-blue-500">
                                <option value="YouTube">YouTube</option>
                                <option value="Twitch">Twitch</option>
                                <option value="TikTok">TikTok</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-400 text-sm font-bold mb-2" for="channel_url">
                                Channel URL
                            </label>
                            <input type="url" name="channel_url" id="channel_url" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white focus:outline-none focus:border-blue-500" placeholder="https://youtube.com/c/yourchannel" required>
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-400 text-sm font-bold mb-2" for="subscriber_count">
                                Subscriber/Follower Count
                            </label>
                            <input type="number" name="subscriber_count" id="subscriber_count" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white focus:outline-none focus:border-blue-500" placeholder="1000" required>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded transition-colors">
                            Submit Application
                        </button>
                    </form>
                @endif
            @else
                <div class="text-center py-8">
                    <p class="text-gray-400 mb-4">Please login to apply for the Creator Program.</p>
                    <a href="{{ route('login') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded transition-colors">
                        Login
                    </a>
                </div>
            @endauth
        </div>
    </div>
</div>
@endsection
