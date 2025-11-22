@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('teams.index') }}" class="text-gray-400 hover:text-white transition-colors flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Back to Teams
            </a>
        </div>

        <div class="bg-gray-800 rounded-xl p-8 border border-gray-700 shadow-lg">
            <h1 class="text-3xl font-bold text-white mb-6">Create New Team</h1>
            
            <form action="{{ route('teams.store') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label for="name" class="block text-gray-300 font-bold mb-2">Team Name</label>
                    <input type="text" name="name" id="name" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-blue-500" placeholder="e.g. The Avengers" required>
                    @error('name')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-8">
                    <label for="description" class="block text-gray-300 font-bold mb-2">Description</label>
                    <textarea name="description" id="description" rows="4" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-blue-500" placeholder="Tell us about your team..."></textarea>
                    @error('description')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-500 hover:to-blue-400 text-white font-bold py-3 rounded-lg shadow-lg transform hover:scale-[1.02] transition-all">
                    Create Team
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
