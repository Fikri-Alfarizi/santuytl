<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserStat;
use App\Models\CoinTransaction;
use Illuminate\Support\Facades\Log;

class CurrencyController extends Controller
{
    /**
     * Add coins to user (called by Discord bot)
     */
    public function addCoins(Request $request)
    {
        // 1. Validasi Secret Key
        $secret = $request->header('X-Discord-Bot-Secret');
        if ($secret !== env('DISCORD_BOT_SECRET')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // 2. Validasi Input
        $request->validate([
            'discord_id' => 'required|string',
            'coins' => 'required|integer|min:1',
        ]);

        // 3. Cari User
        $user = User::where('discord_id', $request->discord_id)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // 4. Tambah Koin
        $stats = UserStat::firstOrCreate(['user_id' => $user->id]);
        $stats->coins += $request->coins;
        $stats->save();

        // 5. Log Transaksi
        CoinTransaction::create([
            'user_id' => $user->id,
            'amount' => $request->coins,
            'type' => 'earn',
            'description' => 'Reward dari aktivitas Discord',
            'metadata' => ['source' => 'discord_bot']
        ]);

        Log::info("Added {$request->coins} coins to {$user->username} via Discord bot.");

        return response()->json([
            'status' => 'success',
            'new_balance' => $stats->coins
        ]);
    }
}
