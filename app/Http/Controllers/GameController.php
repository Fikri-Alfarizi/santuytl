<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Download;
use App\Models\UserStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    /**
     * Display a listing of the games.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $games = Game::orderBy('created_at', 'desc')
            ->paginate(12);
        return view('games.index', compact('games'));
    }

    /**
     * Display the specified game.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        $game = Game::where('id', $slug)->firstOrFail();
        return view('games.show', compact('game'));
    }

    /**
     * Process the game download.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function download($id, Request $request)
    {
        $user = Auth::user();
        $game = Game::findOrFail($id);
        if ($game->access_level === 'vip' && !$user->isVip()) {
            return redirect()->back()->with('error', 'You need VIP access to download this game.');
        }
        if (!$user->isVip()) {
            $todayDownloads = Download::where('user_id', $user->id)
                ->whereDate('created_at', today())
                ->count();
            if ($todayDownloads >= 3) {
                return redirect()->back()->with('error', 'You have reached your daily download limit. Upgrade to VIP for unlimited downloads.');
            }
        }
        $download = Download::create([
            'user_id' => $user->id,
            'game_id' => $game->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'type' => $user->isVip() ? 'direct' : 'indirect',
            'completed' => true,
            'completed_at' => now(),
        ]);
        $userStats = UserStat::firstOrCreate(['user_id' => $user->id]);
        $userStats->increment('games_downloaded');
        $game->increment('download_count');
        $downloadLink = $user->isVip() ? $game->direct_link : $game->indirect_link;
        return redirect()->away($downloadLink);
    }

    /**
     * Search for games.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        $games = Game::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('developer', 'like', "%{$query}%")
                  ->orWhere('publisher', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        return view('games.search', compact('games', 'query'));
    }
}
