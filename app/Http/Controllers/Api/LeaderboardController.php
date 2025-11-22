<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserStat;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'xp');
        $users = UserStat::orderByDesc($type)->with('user')->take(50)->get();
        return view('leaderboard.index', compact('users', 'type'));
    }
}
