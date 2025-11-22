@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-white mb-4">Daily Lucky Wheel</h1>
        <p class="text-gray-400 text-lg">Putar roda keberuntunganmu setiap hari!</p>
    </div>

    <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <!-- Wheel Section -->
        <div class="relative flex justify-center">
            <div class="relative w-80 h-80 md:w-96 md:h-96">
                <!-- Pointer -->
                <div class="absolute top-0 left-1/2 -translate-x-1/2 -mt-4 z-20 text-4xl text-red-500">
                    <i class="fas fa-caret-down"></i>
                </div>

                <!-- Wheel -->
                <div id="wheel" class="w-full h-full rounded-full border-8 border-yellow-500 relative overflow-hidden transition-transform duration-[5000ms] ease-out" style="background: conic-gradient(
                    #EF4444 0deg 60deg, 
                    #3B82F6 60deg 120deg, 
                    #10B981 120deg 180deg, 
                    #F59E0B 180deg 240deg, 
                    #8B5CF6 240deg 300deg, 
                    #EC4899 300deg 360deg
                );">
                    <!-- Segments Text (Simplified for CSS) -->
                    <div class="absolute inset-0 flex items-center justify-center text-white font-bold text-sm">
                        <span class="absolute top-4 left-1/2 -translate-x-1/2 rotate-0">100 Koin</span>
                        <span class="absolute top-1/4 right-4 rotate-60">500 Koin</span>
                        <span class="absolute bottom-1/4 right-4 rotate-120">1000 Koin</span>
                        <span class="absolute bottom-4 left-1/2 -translate-x-1/2 rotate-180">50 XP</span>
                        <span class="absolute bottom-1/4 left-4 rotate-240">200 XP</span>
                        <span class="absolute top-1/4 left-4 rotate-300">500 XP</span>
                    </div>
                </div>

                <!-- Center Cap -->
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-16 h-16 bg-white rounded-full border-4 border-gray-200 shadow-lg flex items-center justify-center z-10">
                    <i class="fas fa-star text-yellow-500 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Controls Section -->
        <div class="text-center md:text-left">
            <div class="bg-gray-800 rounded-xl p-8 border border-gray-700 shadow-lg">
                <h3 class="text-2xl font-bold text-white mb-4">Aturan Main</h3>
                <ul class="text-gray-400 text-sm space-y-2 mb-8 text-left list-disc pl-5">
                    <li>Satu kali spin per hari (reset 00:00).</li>
                    <li>Hadiah langsung masuk ke akun.</li>
                    <li>Kesempatan mendapatkan Jackpot 1000 Koin!</li>
                </ul>

                @if($canSpin)
                    <button id="spinBtn" class="w-full bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-400 hover:to-orange-400 text-white font-bold py-4 px-8 rounded-xl text-xl shadow-lg transform hover:scale-105 transition-all duration-200">
                        PUTAR SEKARANG!
                    </button>
                @else
                    <div class="bg-gray-700 rounded-lg p-4 text-center">
                        <p class="text-gray-400 mb-2">Anda sudah spin hari ini.</p>
                        <p class="text-white font-bold">Kembali lagi besok!</p>
                    </div>
                @endif

                <div id="resultMessage" class="hidden mt-6 p-4 rounded-lg bg-green-500/20 border border-green-500 text-green-400 font-bold text-center animate-bounce">
                    <!-- Result text will appear here -->
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('spinBtn')?.addEventListener('click', function() {
        const btn = this;
        const wheel = document.getElementById('wheel');
        const resultDiv = document.getElementById('resultMessage');

        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');

        // Fetch result from server first
        fetch('{{ route("lucky-wheel.spin") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }

            // Calculate rotation based on reward (Simplified mapping)
            // In real app, map reward ID to angle
            // For demo, we just spin randomly + extra rotations
            const rotations = 5 * 360; // 5 full spins
            const randomOffset = Math.floor(Math.random() * 360); 
            const totalRotation = rotations + randomOffset;

            wheel.style.transform = `rotate(${totalRotation}deg)`;

            setTimeout(() => {
                resultDiv.textContent = data.message;
                resultDiv.classList.remove('hidden');
                
                // Confetti effect could be added here
            }, 5000); // Match CSS transition duration
        })
        .catch(error => {
            console.error('Error:', error);
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
        });
    });
</script>
@endpush
@endsection
