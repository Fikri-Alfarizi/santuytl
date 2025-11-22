<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CommunityController extends Controller
{
    /**
     * Display community dashboard with Discord server statistics
     */
    public function dashboard()
    {
        $stats = null;
        $error = null;
        
        try {
            // Get guild_id from session or config
            $guildId = session('selected_guild_id') ?? config('services.discord.guild_id');
            
            $url = 'http://localhost:3001/server-stats';
            if ($guildId) {
                $url .= '?guild_id=' . $guildId;
            }
            
            $response = Http::timeout(5)->get($url);
            
            if ($response->successful()) {
                $stats = $response->json();
            } else {
                $error = 'Gagal mengambil statistik server';
            }
        } catch (\Exception $e) {
            \Log::error('Failed to fetch server stats: ' . $e->getMessage());
            $error = 'Bot Discord tidak merespons. Pastikan bot sedang running.';
        }
        
        return view('community.dashboard', compact('stats', 'error'));
    }
}
