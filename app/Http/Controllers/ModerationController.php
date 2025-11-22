<?php

namespace App\Http\Controllers;

use App\Models\Strike;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModerationController extends Controller
{
    public function index()
    {
        // Ensure user is admin or moderator (simplified check for now)
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized.');
        }

        $strikes = Strike::with(['user', 'moderator'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        $users = User::all(); // For the dropdown to select user to strike

        return view('admin.moderation.index', compact('strikes', 'users'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'reason' => 'required|string|max:255',
            'severity' => 'required|integer|min:1|max:5',
        ]);

        Strike::create([
            'user_id' => $request->user_id,
            'moderator_id' => Auth::id(),
            'reason' => $request->reason,
            'severity' => $request->severity,
            'expires_at' => now()->addDays(30), // Default 30 days expiration
        ]);

        // Check for auto-ban logic (e.g., 3 active strikes = ban)
        $activeStrikesCount = Strike::where('user_id', $request->user_id)
            ->where('is_active', true)
            ->count();

        if ($activeStrikesCount >= 3) {
            $user = User::find($request->user_id);
            $user->is_banned = true;
            $user->banned_until = now()->addDays(7); // 7 days ban
            $user->save();
            
            return redirect()->back()->with('warning', 'Strike added. User has been automatically banned for 7 days due to reaching 3 strikes.');
        }

        return redirect()->back()->with('success', 'Strike added successfully.');
    }

    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized.');
        }

        $strike = Strike::findOrFail($id);
        $strike->delete();

        return redirect()->back()->with('success', 'Strike removed.');
    }
}
