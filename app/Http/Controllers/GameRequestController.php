<?php

namespace App\Http\Controllers;

use App\Models\GameRequest;
use App\Models\UserStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameRequestController extends Controller
{
    /**
     * Display a listing of the user's game requests.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil data permintaan game dari bot Discord
        $requests = [];
        try {
            $response = \Http::get('http://localhost:3001/game-requests');
            if ($response->successful()) {
                $requests = $response->json('requests') ?? [];
            }
        } catch (\Exception $e) {
            // Jika gagal, biarkan requests kosong
        }
        return view('requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new game request.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Semua user bisa akses form permintaan game
        return view('requests.create');
    }

    /**
     * Store a newly created game request in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'source_link' => 'nullable|url',
        ]);
        // Kirim permintaan ke bot Discord (Express endpoint)
        try {
            $user = Auth::user();
            $username = $user ? ($user->username ?? $user->name ?? 'User') : 'User';
            $content = "[Permintaan Game Baru]\n" . $validated['title'] . "\n" . ($validated['source_link'] ?? '') . "\n" . ($validated['description'] ?? '') . "\nDikirim oleh: " . $username;
            \Http::post('http://localhost:3001/send-message', [
                'channel_id' => '1385912786395336875', // Channel permintaan game
                'message' => $content
            ]);
        } catch (\Exception $e) {
            return redirect()->route('requests.index')->with('error', 'Gagal mengirim permintaan ke Discord.');
        }
        return redirect()->route('requests.index')->with('success', 'Permintaan game berhasil dikirim ke Discord!');
    }

    /**
     * Display the specified game request.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = Auth::user();
        $request = GameRequest::with(['user', 'game', 'processor'])
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        return view('requests.show', compact('request'));
    }
}
