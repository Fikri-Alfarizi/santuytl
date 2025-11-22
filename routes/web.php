<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DiscordAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameRequestController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\VipController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\DiscordBotController;
use App\Http\Controllers\BotInviteController;
use App\Http\Controllers\PrestigeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\GachaController;
use App\Http\Controllers\LuckyWheelController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ReactionTestController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ModerationController;
use App\Http\Controllers\CreatorController;
use App\Http\Controllers\GameFeedController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\VipContentController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\Admin\PanelController;

// Guest routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Discord OAuth routes
Route::get('/auth/discord', [DiscordAuthController::class, 'redirect'])->name('auth.discord');
Route::get('/auth/discord/callback', [DiscordAuthController::class, 'callback'])->name('auth.discord.callback');

// Discord API routes for bot invite
Route::middleware(['auth'])->group(function () {
    Route::get('/api/discord/guilds', [DiscordAuthController::class, 'getUserGuilds'])->name('discord.guilds');
    Route::post('/api/discord/invite', [BotInviteController::class, 'generateInviteUrl'])->name('discord.invite');
    Route::post('/api/discord/check-bot', [BotInviteController::class, 'checkBotInGuild'])->name('discord.check-bot');
});

// Debug route - remove after testing
Route::get('/debug-auth', function() {
    return [
        'authenticated' => Auth::check(),
        'user' => Auth::user(),
        'session' => session()->all(),
    ];
});


// Add a simple login route for Socialite error fallback
Route::get('/login', function () {
    return redirect('/'); // You can change this to a login view if needed
})->name('login');

// Public content routes
Route::get('/games', [GameController::class, 'index'])->name('games.index');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/blog/search', [BlogController::class, 'search'])->name('blog.search');
Route::get('/events', [EventController::class, 'index'])->name('events.index');

// Leaderboard routes
Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');

// Team routes
Route::resource('teams', TeamController::class);
Route::post('/teams/{team}/join', [TeamController::class, 'join'])->name('teams.join');
Route::post('/teams/{team}/leave', [TeamController::class, 'leave'])->name('teams.leave');

// Competition routes
Route::resource('competitions', CompetitionController::class)->only(['index', 'show']);

// Authenticated user routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
    
    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/user', [UserDashboardController::class, 'index'])->name('dashboard.user');
    
    // Community routes
    Route::get('/community', [CommunityController::class, 'dashboard'])->name('community.dashboard');
    
    // Game routes
    Route::post('/games/{id}/download', [GameController::class, 'download'])->name('games.download');
    
    // Game request routes
    Route::get('/requests', [GameRequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/create', [GameRequestController::class, 'create'])->name('requests.create');
    Route::post('/requests', [GameRequestController::class, 'store'])->name('requests.store');
    Route::get('/requests/{id}', [GameRequestController::class, 'show'])->name('requests.show');
    
    // Event routes
    Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');
    Route::post('/events/{id}/register', [EventController::class, 'register'])->name('events.register');
    Route::post('/events/{id}/submit', [EventController::class, 'submit'])->name('events.submit');
    Route::post('/events/vote/{participantId}', [EventController::class, 'vote'])->name('events.vote');
    Route::post('/events/{id}/claim', [EventController::class, 'claimReward'])->name('events.claim');
    Route::post('/events/{id}/finalize', [EventController::class, 'finalizeEvent'])->name('events.finalize');
    
    // VIP routes
    Route::get('/vip', [VipController::class, 'index'])->name('vip.index');
    Route::post('/vip/purchase', [VipController::class, 'purchase'])->name('vip.purchase');
    Route::get('/vip/payment/{id}', [VipController::class, 'payment'])->name('vip.payment');
    Route::post('/vip/confirm-payment/{id}', [VipController::class, 'confirmPayment'])->name('vip.confirm-payment');
    Route::get('/vip/content', [VipContentController::class, 'index'])->name('vip.content');
    

    // Profile routes (API/Advanced)
    Route::get('/profile/{id}', [\App\Http\Controllers\Api\ProfileController::class, 'show'])->name('profile.show');
    
    // Public Profile
    Route::get('/u/{username}', [ProfileController::class, 'show'])->name('profile.public');

    // Forum routes (Web)
    Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
    Route::get('/forum/c/{slug}', [ForumController::class, 'showCategory'])->name('forum.category.show');
    Route::get('/forum/c/{categorySlug}/create', [ForumController::class, 'createThread'])->name('forum.thread.create')->middleware('auth');
    Route::post('/forum/c/{categorySlug}', [ForumController::class, 'storeThread'])->name('forum.thread.store')->middleware('auth');
    Route::get('/forum/c/{categorySlug}/{threadSlug}', [ForumController::class, 'showThread'])->name('forum.thread.show');
    Route::post('/forum/t/{threadId}/reply', [ForumController::class, 'storePost'])->name('forum.post.store')->middleware('auth');

    // Course routes
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');
    Route::post('/courses/{slug}/start', [CourseController::class, 'start'])->name('courses.start')->middleware('auth');
    Route::get('/courses/{slug}/chapter/{chapterId}', [CourseController::class, 'showChapter'])->name('courses.chapter')->middleware('auth');
    Route::post('/courses/{slug}/chapter/{chapterId}/complete', [CourseController::class, 'completeChapter'])->name('courses.chapter.complete')->middleware('auth');

    // Moderation routes
    Route::get('/admin/moderation', [ModerationController::class, 'index'])->name('moderation.index')->middleware('auth');
    Route::post('/admin/moderation', [ModerationController::class, 'store'])->name('moderation.store')->middleware('auth');
    Route::delete('/admin/moderation/{id}', [ModerationController::class, 'destroy'])->name('moderation.destroy')->middleware('auth');

    // Creator Program routes
    Route::get('/creators', [CreatorController::class, 'index'])->name('creators.index');
    Route::post('/creators', [CreatorController::class, 'store'])->name('creators.store')->middleware('auth');
    Route::get('/admin/creators', [CreatorController::class, 'adminIndex'])->name('creators.admin')->middleware('auth');
    Route::post('/admin/creators/{id}/approve', [CreatorController::class, 'approve'])->name('creators.approve')->middleware('auth');
    Route::post('/admin/creators/{id}/reject', [CreatorController::class, 'reject'])->name('creators.reject')->middleware('auth');

    // Game Feed routes
    Route::get('/games', [GameFeedController::class, 'index'])->name('games.index');

    // Forum routes (API/Advanced) - Keeping for reference or API usage
    // Route::get('/forum', [\App\Http\Controllers\Api\ForumController::class, 'index'])->name('forum.index.api');
    // Route::get('/forum/{id}', [\App\Http\Controllers\Api\ForumController::class, 'show'])->name('forum.show.api');
    // Event routes (API/Advanced)
    Route::get('/event', [\App\Http\Controllers\Api\EventController::class, 'index'])->name('event.index');
    // Market routes (API/Advanced)
    Route::get('/marketplace', [\App\Http\Controllers\Api\MarketController::class, 'index'])->name('marketplace.index');
    // Bank routes (API/Advanced)
    Route::get('/banking', [\App\Http\Controllers\Api\BankController::class, 'index'])->name('banking.index');
    // Gacha (API/Advanced)
    Route::post('/gacha/spin', [\App\Http\Controllers\Api\GachaController::class, 'spin'])->name('gacha.spin.api');
    // Lucky Wheel (API/Advanced)
    Route::post('/lucky-wheel/spin', [\App\Http\Controllers\Api\LuckyWheelController::class, 'spin'])->name('lucky-wheel.spin.api');
    // Quiz (API/Advanced)
    Route::post('/quiz/answer', [\App\Http\Controllers\Api\QuizController::class, 'answer'])->name('quiz.answer.api');
    // Reaction Test (API/Advanced)
    Route::post('/reaction-test/submit', [\App\Http\Controllers\Api\ReactionTestController::class, 'submit'])->name('reaction-test.submit.api');
    // Tutorial (API/Advanced)
    Route::get('/tutorial', [\App\Http\Controllers\Api\TutorialController::class, 'index'])->name('tutorial.index');
    // Mentor (API/Advanced)
    Route::post('/mentor/book', [\App\Http\Controllers\Api\MentorController::class, 'book'])->name('mentor.book');
    Route::post('/mentor/rate', [\App\Http\Controllers\Api\MentorController::class, 'rate'])->name('mentor.rate');
    // Strike (API/Advanced)
    Route::get('/strike', [\App\Http\Controllers\Api\StrikeController::class, 'index'])->name('strike.index');
    Route::post('/strike/give', [\App\Http\Controllers\Api\StrikeController::class, 'give'])->name('strike.give');
    // Creator Program (API/Advanced)
    Route::get('/creator/feed', [\App\Http\Controllers\Api\CreatorController::class, 'feed'])->name('creator.feed');
    Route::post('/creator/submit', [\App\Http\Controllers\Api\CreatorController::class, 'submit'])->name('creator.submit');
    // Analytics (Web)
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

    // Prestige System
    Route::get('/prestige', [PrestigeController::class, 'index'])->name('prestige.index');
    Route::post('/prestige/do', [PrestigeController::class, 'prestige'])->name('prestige.do');
    Route::get('/prestige/history', [PrestigeController::class, 'history'])->name('prestige.history');

    // Job/Class System
    Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
    Route::post('/jobs/select', [JobController::class, 'select'])->name('jobs.select');
    Route::post('/jobs/change', [JobController::class, 'change'])->name('jobs.change');

    // Inventory System
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('/inventory/equip', [InventoryController::class, 'equip'])->name('inventory.equip');

    // Market System
    Route::get('/market', [MarketController::class, 'index'])->name('market.index');
    Route::get('/market/sell', [MarketController::class, 'sell'])->name('market.sell');
    Route::post('/market/store', [MarketController::class, 'store'])->name('market.store');
    Route::post('/market/buy', [MarketController::class, 'buy'])->name('market.buy');

    // Bank System
    Route::get('/bank', [BankController::class, 'index'])->name('bank.index');
    Route::post('/bank/deposit', [BankController::class, 'deposit'])->name('bank.deposit');
    Route::post('/bank/withdraw', [BankController::class, 'withdraw'])->name('bank.withdraw');

    // Trade System
    Route::get('/trades', [TradeController::class, 'index'])->name('trades.index');
    Route::get('/trades/create', [TradeController::class, 'create'])->name('trades.create');
    Route::post('/trades/store', [TradeController::class, 'store'])->name('trades.store');
    Route::post('/trades/{trade}/accept', [TradeController::class, 'accept'])->name('trades.accept');
    Route::post('/trades/{trade}/reject', [TradeController::class, 'reject'])->name('trades.reject');
    Route::post('/trades/{trade}/cancel', [TradeController::class, 'cancel'])->name('trades.cancel');

    // Mini Games
    Route::get('/gacha', [GachaController::class, 'index'])->name('gacha.index');
    Route::post('/gacha/spin', [GachaController::class, 'spin'])->name('gacha.spin');

    Route::get('/lucky-wheel', [LuckyWheelController::class, 'index'])->name('lucky-wheel.index');
    Route::post('/lucky-wheel/spin', [LuckyWheelController::class, 'spin'])->name('lucky-wheel.spin');

    Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.index');
    Route::post('/quiz/submit', [QuizController::class, 'submit'])->name('quiz.submit');

    Route::get('/reaction-test', [ReactionTestController::class, 'index'])->name('reaction-test.index');
    Route::post('/reaction-test/submit', [ReactionTestController::class, 'submit'])->name('reaction-test.submit');
    
    // Ticket routes
    Route::resource('tickets', TicketController::class)->only(['index', 'create', 'store', 'show']);
    
    // Staff routes
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
});

// Admin routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/requests', [AdminController::class, 'gameRequests'])->name('requests');
    Route::post('/requests/{id}/status', [AdminController::class, 'updateRequestStatus'])->name('requests.update-status');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{id}', [AdminController::class, 'userDetails'])->name('users.details');
    Route::post('/users/{id}/ban', [AdminController::class, 'toggleUserBan'])->name('users.ban');
    
    // Admin panel (Moderator/Owner only)
    Route::get('/panel', [PanelController::class, 'index'])
        ->name('panel')
        ->middleware(['discord.role:Moderator,Owner']);
    
    // Discord Bot Controls (Owner Only)
    Route::middleware(['owner'])->group(function () {
        // Server Selection
        Route::get('/discord/select-server', [DiscordBotController::class, 'selectServer'])->name('discord.select-server');
        Route::post('/discord/set-server', [DiscordBotController::class, 'setServer'])->name('discord.set-server');
        
        // Bot Status
        Route::get('/discord/status', [DiscordBotController::class, 'botStatus'])->name('discord.status');
        
        // Send Message & DM
        Route::get('/discord/send-message', [DiscordBotController::class, 'showSendMessageForm'])->name('discord.send-message.form');
        Route::post('/discord/send-message', [DiscordBotController::class, 'sendMessage'])->name('discord.send-message');

        Route::get('/discord/send-dm', [DiscordBotController::class, 'showSendDmForm'])->name('discord.send-dm.form');
        Route::post('/discord/send-dm', [DiscordBotController::class, 'sendDm'])->name('discord.send-dm');

        // User Management
        Route::get('/discord/kick', [DiscordBotController::class, 'showKickForm'])->name('discord.kick.form');
        Route::post('/discord/kick', [DiscordBotController::class, 'kickUser'])->name('discord.kick');

        Route::get('/discord/ban', [DiscordBotController::class, 'showBanForm'])->name('discord.ban.form');
        Route::post('/discord/ban', [DiscordBotController::class, 'banUser'])->name('discord.ban');

        // Role Management
        Route::get('/discord/assign-role', [DiscordBotController::class, 'showAssignRoleForm'])->name('discord.assign-role.form');
        Route::post('/discord/assign-role', [DiscordBotController::class, 'assignRole'])->name('discord.assign-role');

        Route::get('/discord/remove-role', [DiscordBotController::class, 'showRemoveRoleForm'])->name('discord.remove-role.form');
        Route::post('/discord/remove-role', [DiscordBotController::class, 'removeRole'])->name('discord.remove-role');
        
        // Voice Channel Control
        Route::get('/discord/voice', [DiscordBotController::class, 'showVoiceForm'])->name('discord.voice.form');
        Route::post('/discord/voice/join', [DiscordBotController::class, 'joinVoice'])->name('discord.voice.join');
        Route::post('/discord/voice/leave', [DiscordBotController::class, 'leaveVoice'])->name('discord.voice.leave');
        Route::get('/discord/voice/status', [DiscordBotController::class, 'voiceStatus'])->name('discord.voice.status');
    });
});