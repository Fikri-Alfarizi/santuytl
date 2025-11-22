<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class DiscordLeaderboardController extends Controller
{
    public function index()
    {
        // Ambil data user Discord dan XP dari bot
        $users = [];
        try {
            $response = Http::get('http://localhost:3001/users');
            if ($response->successful()) {
                $users = $response->json('users') ?? [];
            }
        } catch (\Exception $e) {
            // Jika gagal, biarkan users kosong
        }
        // Ambil XP dari pesan (opsional: bisa dari endpoint lain jika ada)
        // Di sini diasumsikan XP sudah diakumulasi di bot dan dikirim ke Laravel
        // Jika tidak, perlu endpoint khusus di bot untuk leaderboard XP
        usort($users, function($a, $b) {
            return ($b['xp'] ?? 0) <=> ($a['xp'] ?? 0);
        });
        return response()->json(['leaderboard' => $users]);
    }
}
