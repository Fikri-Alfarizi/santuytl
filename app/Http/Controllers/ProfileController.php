<?php

namespace App\Http\Controllers;

use App\Models\UserStat;
use App\Models\UserBadge;
use App\Models\Badge;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userStats = UserStat::where('user_id', $user->id)->first();
        $userBadges = UserBadge::where('user_id', $user->id)
            ->with('badge')
            ->get();
        $allBadges = Badge::where('is_active', true)->get();
        return view('profile.index', compact('user', 'userStats', 'userBadges', 'allBadges'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            // Store the full URL for the avatar
            $validated['avatar'] = asset('storage/' . $avatarPath);
        }
        $user->update($validated);
        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
        if (!Hash::check($validated['current_password'], $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);
        return redirect()->back()->with('success', 'Password updated successfully.');
    }

    public function show($username)
    {
        $user = \App\Models\User::where('name', $username)->firstOrFail();
        $userStats = UserStat::where('user_id', $user->id)->first();
        $userBadges = UserBadge::where('user_id', $user->id)->with('badge')->get();
        
        // Get recent activity or teams
        $teamMember = \App\Models\TeamMember::where('user_id', $user->id)->with('team')->first();
        $team = $teamMember ? $teamMember->team : null;

        // Get history
        $history = \App\Models\UserHistory::where('user_id', $user->id)->orderBy('created_at', 'desc')->take(10)->get();

        return view('profile.public', compact('user', 'userStats', 'userBadges', 'team', 'history'));
    }
}
