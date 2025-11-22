@extends('layouts.app')

@section('title', 'Discord Events')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-2xl font-bold mb-6">Upcoming and Ongoing Events</h3>

                    @if (isset($error))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ $error }}</span>
                        </div>
                    @endif

                    @if ($events->isEmpty())
                        <p class="text-gray-600">No upcoming or ongoing events found.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($events as $event)
                                <div class="bg-gray-100 rounded-lg shadow-md p-6 flex flex-col justify-between event-card relative overflow-hidden"
                                     data-start-time="{{ $event->starts_at ? $event->starts_at->toISOString() : '' }}"
                                     data-end-time="{{ $event->ends_at ? $event->ends_at->toISOString() : '' }}">
                                    
                                    <!-- Tier Badge -->
                                    <div class="absolute top-0 right-0 px-3 py-1 text-xs font-bold uppercase text-white
                                        @if($event->tier == 'daily') bg-blue-500
                                        @elseif($event->tier == 'weekly') bg-purple-500
                                        @elseif($event->tier == 'monthly') bg-red-500
                                        @else bg-gray-500 @endif
                                        rounded-bl-lg">
                                        {{ $event->tier }}
                                    </div>

                                    <div>
                                        @if ($event->banner_image)
                                            <img src="{{ $event->banner_image }}" alt="{{ $event->title }}" class="rounded-lg mb-4 object-cover w-full h-48">
                                        @endif
                                        <h4 class="text-xl font-semibold text-gray-900 mb-2">{{ $event->title }}</h4>
                                        <p class="text-gray-700 text-sm mb-4">{{ Str::limit($event->description, 100) }}</p>
                                        
                                        <div class="flex flex-wrap gap-2 mb-4">
                                            @if($event->xp_reward)
                                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">XP: {{ $event->xp_reward }}</span>
                                            @endif
                                            @if($event->coin_reward)
                                                <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">Coins: {{ $event->coin_reward }}</span>
                                            @endif
                                        </div>

                                        <p class="text-gray-600 text-xs">Starts: <span class="font-medium">{{ $event->starts_at ? $event->starts_at->format('M d, Y H:i') : 'TBA' }}</span></p>
                                        @if ($event->ends_at)
                                            <p class="text-gray-600 text-xs">Ends: <span class="font-medium">{{ $event->ends_at->format('M d, Y H:i') }}</span></p>
                                        @endif
                                        <p class="text-sm font-bold mt-2 status-text">Status: <span class="event-status"></span></p>
                                    </div>
                                    <div class="mt-4">
                                        <div class="text-center text-lg font-bold countdown-timer"></div>
                                        <a href="{{ route('events.show', $event->slug) }}" class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center w-full transition-colors">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function updateCountdown() {
                document.querySelectorAll('.event-card').forEach(card => {
                    const startTime = new Date(card.dataset.startTime);
                    const endTime = card.dataset.endTime ? new Date(card.dataset.endTime) : null;
                    const now = new Date();
                    const timerDisplay = card.querySelector('.countdown-timer');
                    const statusDisplay = card.querySelector('.event-status');
                    let status = '';
                    let timeLeft = '';

                    if (now < startTime) {
                        // Event has not started yet (Upcoming)
                        const diff = startTime - now;
                        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                        timeLeft = `${days}d ${hours}h ${minutes}m ${seconds}s`;
                        status = 'Upcoming';
                        timerDisplay.classList.remove('text-red-600', 'text-green-600');
                        timerDisplay.classList.add('text-blue-600');
                    } else if (endTime && now < endTime) {
                        // Event is ongoing
                        const diff = endTime - now;
                        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                        timeLeft = `Ends in: ${days}d ${hours}h ${minutes}m ${seconds}s`;
                        status = 'Ongoing';
                        timerDisplay.classList.remove('text-blue-600', 'text-red-600');
                        timerDisplay.classList.add('text-green-600');
                    } else {
                        // Event has ended
                        timeLeft = 'Ended';
                        status = 'Ended';
                        timerDisplay.classList.remove('text-blue-600', 'text-green-600');
                        timerDisplay.classList.add('text-red-600');
                    }

                    timerDisplay.textContent = timeLeft;
                    statusDisplay.textContent = status;
                });
            }

            // Update countdown every second
            setInterval(updateCountdown, 1000);
            updateCountdown(); // Initial call to display immediately
        </script>
    @endpush
@endsection