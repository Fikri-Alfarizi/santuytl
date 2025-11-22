<?php

namespace App\Http\Controllers;

use App\Models\AnalyticsDaily;
use App\Models\UserStat;
use App\Models\User;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Get last 30 days of data
        $dailyStats = AnalyticsDaily::orderBy('date', 'asc')->take(30)->get();
        
        $totalUsers = User::count();
        $activeToday = UserStat::where('last_active_at', '>=', now()->startOfDay())->count();
        $newUsersToday = User::where('created_at', '>=', now()->startOfDay())->count();
        
        return view('analytics.index', compact('dailyStats', 'totalUsers', 'activeToday', 'newUsersToday'));
    }
}
