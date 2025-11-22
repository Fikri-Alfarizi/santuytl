<?php

namespace App\Http\Controllers;

use App\Models\UserStat;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index()
    {
        $xpLeaderboard = UserStat::with('user')->orderBy('xp', 'desc')->take(10)->get();
        $coinLeaderboard = UserStat::with('user')->orderBy('coins', 'desc')->take(10)->get();
        $weeklyLeaderboard = UserStat::with('user')->orderBy('weekly_xp', 'desc')->take(10)->get();

        return view('leaderboard.index', compact('xpLeaderboard', 'coinLeaderboard', 'weeklyLeaderboard'));
    }
}
