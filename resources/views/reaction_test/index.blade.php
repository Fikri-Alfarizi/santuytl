@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto text-center">
        <h1 class="text-3xl font-bold text-white mb-4">Reaction Test</h1>
        <p class="text-gray-400 mb-8">Klik secepat mungkin saat layar berubah warna menjadi HIJAU!</p>

        <div id="gameArea" class="w-full h-80 bg-red-500 rounded-xl shadow-lg cursor-pointer flex items-center justify-center transition-colors select-none relative overflow-hidden">
            <div class="text-white font-bold text-2xl z-10" id="gameText">
                Klik untuk Mulai
            </div>
            <!-- Ripple effect container -->
            <div class="absolute inset-0 pointer-events-none" id="rippleContainer"></div>
        </div>

        <div class="mt-8 grid grid-cols-2 gap-4">
            <div class="bg-gray-800 p-4 rounded-lg border border-gray-700">
                <div class="text-gray-400 text-xs uppercase">Waktu Terakhir</div>
                <div class="text-2xl font-bold text-white" id="lastTime">-</div>
            </div>
            <div class="bg-gray-800 p-4 rounded-lg border border-gray-700">
                <div class="text-gray-400 text-xs uppercase">Best Time</div>
                <div class="text-2xl font-bold text-yellow-500" id="bestTime">-</div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const gameArea = document.getElementById('gameArea');
    const gameText = document.getElementById('gameText');
    const lastTimeDisplay = document.getElementById('lastTime');
    const bestTimeDisplay = document.getElementById('bestTime');

    let startTime;
    let timeoutId;
    let gameState = 'waiting'; // waiting, ready, now, result
    let bestTime = Infinity;

    gameArea.addEventListener('mousedown', handleClick);

    function handleClick() {
        if (gameState === 'waiting') {
            startGame();
        } else if (gameState === 'ready') {
            tooSoon();
        } else if (gameState === 'now') {
            endGame();
        } else if (gameState === 'result') {
            startGame();
        }
    }

    function startGame() {
        gameState = 'ready';
        gameArea.className = 'w-full h-80 bg-red-500 rounded-xl shadow-lg cursor-pointer flex items-center justify-center transition-colors select-none relative overflow-hidden';
        gameText.textContent = 'Tunggu Warna Hijau...';
        
        const randomDelay = Math.floor(Math.random() * 3000) + 2000; // 2-5 seconds

        timeoutId = setTimeout(() => {
            gameState = 'now';
            gameArea.className = 'w-full h-80 bg-green-500 rounded-xl shadow-lg cursor-pointer flex items-center justify-center transition-colors select-none relative overflow-hidden';
            gameText.textContent = 'KLIK SEKARANG!';
            startTime = Date.now();
        }, randomDelay);
    }

    function tooSoon() {
        clearTimeout(timeoutId);
        gameState = 'result';
        gameArea.className = 'w-full h-80 bg-blue-500 rounded-xl shadow-lg cursor-pointer flex items-center justify-center transition-colors select-none relative overflow-hidden';
        gameText.textContent = 'Terlalu Cepat! Klik untuk ulang.';
    }

    function endGame() {
        const endTime = Date.now();
        const reactionTime = endTime - startTime;
        gameState = 'result';
        
        lastTimeDisplay.textContent = reactionTime + ' ms';
        if (reactionTime < bestTime) {
            bestTime = reactionTime;
            bestTimeDisplay.textContent = bestTime + ' ms';
        }

        gameText.textContent = `${reactionTime} ms! Klik untuk ulang.`;
        gameArea.className = 'w-full h-80 bg-gray-800 rounded-xl shadow-lg cursor-pointer flex items-center justify-center transition-colors select-none relative overflow-hidden border border-gray-600';

        // Send score to server
        fetch('{{ route("reaction-test.submit") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ reaction_time: reactionTime })
        })
        .then(res => res.json())
        .then(data => {
            if (data.coins > 0) {
                gameText.innerHTML = `${reactionTime} ms!<br><span class="text-sm text-yellow-400">${data.message}</span>`;
            }
        });
    }
</script>
@endpush
@endsection
