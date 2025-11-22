<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Gamification Settings
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk sistem gamifikasi
    |
    */

    // Level System
    'max_level' => env('GAMIFICATION_MAX_LEVEL', 100),
    'xp_base' => env('GAMIFICATION_XP_BASE', 100),
    'xp_multiplier' => env('GAMIFICATION_XP_MULTIPLIER', 1.5),

    // XP Rewards
    'xp_rewards' => [
        'message' => 5,
        'voice_minute' => 10,
        'event_participate' => 50,
        'event_win' => 200,
        'game_download' => 25,
        'request_approved' => 100,
        'daily_login' => 20,
    ],

    // Prestige System
    'prestige' => [
        'enabled' => true,
        'min_level' => 100,
        'rewards' => [
            'badge' => true,
            'special_role' => true,
            'coins_bonus' => 1000,
        ],
    ],

    // Job/Class System
    'jobs' => [
        'change_cooldown_days' => 7, // Cooldown untuk ganti job
        'change_cost_coins' => 500, // Biaya ganti job
    ],

    // Coin System
    'coins' => [
        'daily_login' => 50,
        'level_up' => 100,
        'prestige' => 1000,
    ],
];
