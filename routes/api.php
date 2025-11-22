<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DiscordWebhookController;
use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\Api\GameEventSyncController;
use App\Http\Controllers\Api\LeaderboardController;
use App\Http\Controllers\Api\ForumController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\MarketController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\BankController;
use App\Http\Controllers\Api\GachaController;
use App\Http\Controllers\Api\LuckyWheelController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\ReactionTestController;
use App\Http\Controllers\Api\TutorialController;
use App\Http\Controllers\Api\MentorController;
use App\Http\Controllers\Api\StrikeController;
use App\Http\Controllers\Api\CreatorController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Route untuk webhook bot Discord
Route::post('/discord/webhook', [DiscordWebhookController::class, 'handle'])
    ->middleware('throttle:60,1'); // Batasi 60 permintaan per menit untuk mencegah spam

// Endpoint untuk menambah koin dari bot Discord
Route::post('/add-coins', [CurrencyController::class, 'addCoins'])
    ->middleware('throttle:60,1');

// Endpoint untuk sinkronisasi Game & Event dari bot Discord
Route::post('/discord/game', [GameEventSyncController::class, 'storeGame']);
Route::post('/discord/event', [GameEventSyncController::class, 'storeEvent']);

// Endpoint GET untuk testing webhook (tidak untuk produksi)
Route::get('/discord/webhook', function(Request $request) {
    return response()->json(['status' => 'ok', 'message' => 'GET method for testing only. Use POST for real data.']);
});

// Leaderboard
Route::get('/leaderboard', [LeaderboardController::class, 'index']);

// Forum
Route::apiResource('forum', ForumController::class);
Route::post('forum/{id}/like', [ForumController::class, 'like']);
Route::post('forum/{id}/dislike', [ForumController::class, 'dislike']);

// Public Profile
Route::get('profile/{id}', [ProfileController::class, 'show']);

// Event
Route::get('events', [EventController::class, 'index']);
Route::post('events/join', [EventController::class, 'join']);
Route::post('events/vote', [EventController::class, 'vote']);

// Market & Inventory
Route::get('market', [MarketController::class, 'index']);
Route::post('market/buy', [MarketController::class, 'buy']);
Route::post('market/sell', [MarketController::class, 'sell']);
Route::get('inventory', [InventoryController::class, 'index']);

// Bank
Route::post('bank/deposit', [BankController::class, 'deposit']);
Route::post('bank/withdraw', [BankController::class, 'withdraw']);

// Mini Games
Route::post('gacha/spin', [GachaController::class, 'spin']);
Route::post('luckywheel/spin', [LuckyWheelController::class, 'spin']);
Route::post('quiz/answer', [QuizController::class, 'answer']);
Route::post('reactiontest/submit', [ReactionTestController::class, 'submit']);

// Tutorial & Mentor
Route::get('tutorials', [TutorialController::class, 'index']);
Route::post('mentor/book', [MentorController::class, 'book']);
Route::post('mentor/rate', [MentorController::class, 'rate']);

// Moderation
Route::get('strikes', [StrikeController::class, 'index']);
Route::post('strikes/give', [StrikeController::class, 'give']);

// Creator Program
Route::post('creator/submit', [CreatorController::class, 'submit']);
Route::get('creator/feed', [CreatorController::class, 'feed']);

// Discord Integration (lanjutan)
Route::post('discord/add-xp', [DiscordWebhookController::class, 'addXp']);
Route::post('discord/add-coins', [DiscordWebhookController::class, 'addCoins']);