<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\GameHistory;
use App\Models\CoinTransaction;
use App\Models\UserStat;
use Carbon\Carbon;

class LuckyWheelController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Check if played today
        $lastPlayed = GameHistory::where('user_id', $user->id)
            ->where('game_type', 'lucky_wheel')
            ->whereDate('played_at', Carbon::today())
            ->first();
            
        $canSpin = !$lastPlayed;
        
        return view('lucky_wheel.index', compact('user', 'canSpin'));
    }

    public function spin(Request $request)
    {
        $user = Auth::user();

        // Check daily limit
        $lastPlayed = GameHistory::where('user_id', $user->id)
            ->where('game_type', 'lucky_wheel')
            ->whereDate('played_at', Carbon::today())
            ->first();

        if ($lastPlayed) {
            return response()->json(['error' => 'Anda sudah melakukan spin hari ini. Kembali lagi besok!'], 400);
        }

        // Rewards configuration
        $rewards = [
            ['type' => 'coins', 'amount' => 100, 'chance' => 30, 'label' => '100 Koin'],
            ['type' => 'coins', 'amount' => 500, 'chance' => 15, 'label' => '500 Koin'],
            ['type' => 'coins', 'amount' => 1000, 'chance' => 5, 'label' => '1000 Koin'],
            ['type' => 'xp', 'amount' => 50, 'chance' => 30, 'label' => '50 XP'],
            ['type' => 'xp', 'amount' => 200, 'chance' => 15, 'label' => '200 XP'],
            ['type' => 'xp', 'amount' => 500, 'chance' => 5, 'label' => '500 XP'],
        ];

        // Random logic
        $totalChance = array_sum(array_column($rewards, 'chance'));
        $random = mt_rand(1, $totalChance);
        $current = 0;
        $wonReward = null;

        foreach ($rewards as $reward) {
            $current += $reward['chance'];
            if ($random <= $current) {
                $wonReward = $reward;
                break;
            }
        }

        DB::transaction(function () use ($user, $wonReward) {
            $stats = UserStat::firstOrCreate(['user_id' => $user->id]);

            if ($wonReward['type'] == 'coins') {
                $stats->coins += $wonReward['amount'];
                CoinTransaction::create([
                    'user_id' => $user->id,
                    'amount' => $wonReward['amount'],
                    'type' => 'earn',
                    'description' => 'Lucky Wheel Reward',
                ]);
            } elseif ($wonReward['type'] == 'xp') {
                $stats->xp += $wonReward['amount'];
                // Level up logic should be centralized, but simple addition here
                // Ideally trigger event or use service
            }
            
            $stats->save();

            GameHistory::create([
                'user_id' => $user->id,
                'game_type' => 'lucky_wheel',
                'result' => ['won' => true],
                'reward' => $wonReward,
                'played_at' => now(),
            ]);
        });

        return response()->json([
            'success' => true,
            'reward' => $wonReward,
            'message' => "Selamat! Anda mendapatkan {$wonReward['label']}!"
        ]);
    }
}
