<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PrestigeHistory;

class PrestigeController extends Controller
{
    /**
     * Show prestige page
     */
    public function index()
    {
        $user = Auth::user();
        $stats = $user->userStat;
        
        // Ensure stats exist
        if (!$stats) {
            $stats = $user->userStat()->create([
                'xp' => 0, 'level' => 1, 'xp_to_next_level' => 100
            ]);
        }

        $maxLevel = config('gamification.max_level', 100);
        $canPrestige = $stats->canPrestige();
        
        // Calculate progress percentage to max level
        $progress = min(100, ($stats->level / $maxLevel) * 100);

        return view('prestige.index', compact('user', 'stats', 'maxLevel', 'canPrestige', 'progress'));
    }

    /**
     * Perform prestige action
     */
    public function prestige(Request $request)
    {
        $user = Auth::user();
        $stats = $user->userStat;

        if (!$stats || !$stats->canPrestige()) {
            return back()->with('error', 'Anda belum memenuhi syarat untuk melakukan Prestige!');
        }

        // Perform prestige
        $stats->doPrestige();

        return redirect()->route('prestige.index')->with('success', 'Selamat! Anda berhasil melakukan Prestige! Level Anda telah direset namun Prestige Level bertambah.');
    }

    /**
     * Show prestige history
     */
    public function history()
    {
        $user = Auth::user();
        $history = $user->userStat->prestigeHistory()->orderBy('created_at', 'desc')->paginate(10);

        return view('prestige.history', compact('user', 'history'));
    }
}
