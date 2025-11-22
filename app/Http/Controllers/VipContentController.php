<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Game;

class VipContentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isVip = false;

        // Cek role Discord user (asumsi sudah ada sinkronisasi role di user model)
        if ($user && method_exists($user, 'hasDiscordRole')) {
            $isVip = $user->hasDiscordRole('VIP');
        }

        $games = Game::where('is_vip', true)->get();

        return view('vip.index', compact('games', 'isVip'));
    }
}
