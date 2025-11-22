<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $discordId = $user->discord_id ?? $user->discord_id ?? $user->id; // fallback jika discord_id tidak ada
        $botApiUrl = env('DISCORD_BOT_API_URL', 'http://localhost:3000');
        $stats = null;
        $error = null;
        try {
            $response = Http::get("$botApiUrl/user-stats/{$discordId}");
            if ($response->successful()) {
                $stats = $response->json('stats');
            } else {
                $error = 'Gagal mengambil data statistik dari bot.';
            }
        } catch (\Exception $e) {
            $error = 'Bot Discord tidak dapat dihubungi.';
        }
        return view('dashboard.user', compact('stats', 'error'));
    }
}
