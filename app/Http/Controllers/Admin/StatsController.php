<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserStat;

class StatsController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalXp = UserStat::sum('xp');
        $activeUsers = UserStat::where('last_active_at', '>=', now()->subDays(7))->count();
        return view('admin.stats', compact('totalUsers', 'totalXp', 'activeUsers'));
    }
}
