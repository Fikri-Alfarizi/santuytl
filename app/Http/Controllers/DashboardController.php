<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{

    /**
     * Show the leaderboard page.
     */
    public function leaderboard()
    {
        $leaderboard = \App\Models\User::with('userStat')
            ->whereHas('userStat')
            ->orderByDesc(\DB::raw('COALESCE((SELECT xp FROM user_stats WHERE user_id = users.id), 0)'))
            ->take(50)
            ->get();
        return view('leaderboard.index', [
            'leaderboard' => $leaderboard
        ]);
    }
    
    /**
     * Display the user dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil top 10 leaderboard user berdasarkan XP
        $leaderboard = \App\Models\User::with('userStat')
            ->whereHas('userStat')
            ->orderByDesc(\DB::raw('COALESCE((SELECT xp FROM user_stats WHERE user_id = users.id), 0)'))
            ->take(10)
            ->get();
        return view('dashboard.index', [
            'leaderboard' => $leaderboard
        ]);
    }
}