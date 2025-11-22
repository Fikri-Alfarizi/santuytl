@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-gray-800 rounded-lg shadow-lg border border-gray-700 p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">Daily Quiz</h1>
            <p class="text-gray-400">Jawab pertanyaan matematika sederhana untuk mendapatkan hadiah!</p>
        </div>

        @if(session('success'))
            <div class="bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded mb-6 text-center">
                {{ session('success') }}
                <div class="mt-2">
                    <a href="{{ route('quiz.index') }}" class="text-sm underline hover:text-green-300">Soal Baru</a>
                </div>
            </div>
        @elseif(session('error'))
            <div class="bg-red-500/20 border border-red-500 text-red-400 px-4 py-3 rounded mb-6 text-center">
                {{ session('error') }}
            </div>
        @endif

        @if(!session('success'))
            <div class="text-center mb-8">
                <div class="text-6xl font-bold text-blue-400 font-mono tracking-wider">
                    {{ $question }} = ?
                </div>
            </div>

            <form action="{{ route('quiz.submit') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $encryptedAnswer }}">
                
                <div class="mb-6">
                    <input type="number" name="answer" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white text-center text-xl focus:outline-none focus:border-blue-500" placeholder="Jawaban Anda" required autofocus>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition-colors">
                    Jawab
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
