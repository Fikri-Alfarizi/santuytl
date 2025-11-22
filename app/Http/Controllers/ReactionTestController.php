<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CoinTransaction;
use App\Models\GameHistory;

class ReactionTestController extends Controller
{
    public function index()
    {
        return view('reaction_test.index');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'reaction_time' => 'required|integer|min:1', // ms
        ]);

        $user = Auth::user();
        $time = $request->reaction_time;

        // Anti-cheat: Human limit is around 100-150ms. 
        if ($time < 100) {
            return response()->json(['error' => 'Too fast! Are you a robot?'], 400);
        }

        // Reward logic based on speed
        $coins = 0;
        $xp = 0;

        if ($time < 200) { // Godlike
            $coins = 50;
            $xp = 20;
        } elseif ($time < 300) { // Fast
            $coins = 20;
            $xp = 10;
        } elseif ($time < 400) { // Average
            $coins = 5;
            $xp = 5;
        }

        if ($coins > 0) {
            $user->stats->coins += $coins;
            $user->stats->xp += $xp;
            $user->stats->save();

            CoinTransaction::create([
                'user_id' => $user->id,
                'amount' => $coins,
                'type' => 'earn',
                'description' => "Reaction Test: {$time}ms",
            ]);

            GameHistory::create([
                'user_id' => $user->id,
                'game_type' => 'reaction_test',
                'result' => ['time' => $time],
                'reward' => ['coins' => $coins, 'xp' => $xp],
                'played_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => "Hebat! Waktu: {$time}ms. Dapat $coins Koin & $xp XP.",
                'coins' => $coins
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Waktu: {$time}ms. Terlalu lambat untuk hadiah, coba lagi!",
            'coins' => 0
        ]);
    }
}
