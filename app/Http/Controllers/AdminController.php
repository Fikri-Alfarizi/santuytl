<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserStat;
use App\Models\GameRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard()
    {
        // Ambil statistik utama
        $totalUsers = User::count();
        $totalVipUsers = User::where('role', 'vip')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalXpGiven = UserStat::sum('xp');
        $pendingRequests = GameRequest::where('status', 'pending')->count();

        // Ambil aktivitas terbaru
        $latestUsers = User::latest()->take(5)->get();
        $latestRequests = GameRequest::with('user')->latest()->take(5)->get();
        
        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalVipUsers', 
            'totalAdmins', 
            'totalXpGiven', 
            'pendingRequests',
            'latestUsers',
            'latestRequests'
        ));
    }

    /**
     * Display all users.
     */
    public function users()
    {
        // Check if owner has selected a server
        if (auth()->user()->isOwner() && !session('selected_guild_id')) {
            return redirect()->route('admin.discord.select-server')
                ->with('warning', 'Silakan pilih server terlebih dahulu untuk mengelola pengguna.');
        }

        $guildId = session('selected_guild_id');
        
        // Ambil data user dari bot Discord dengan filter guild_id jika ada
        $discordUsers = [];
        try {
            $url = 'http://localhost:3001/users';
            if ($guildId) {
                $response = \Http::get($url);
                if ($response->successful()) {
                    $allUsers = $response->json('users') ?? [];
                    $discordUsers = $allUsers;
                }
            } else {
                $response = \Http::get($url);
                if ($response->successful()) {
                    $discordUsers = $response->json('users') ?? [];
                }
            }
        } catch (\Exception $e) {
            // Jika gagal, biarkan users kosong
        }
        
        // Gabungkan dengan data dari database Laravel
        $users = [];
        foreach ($discordUsers as $discordUser) {
            // Cari user di database berdasarkan discord_id
            $dbUser = User::with('userStat')->where('discord_id', $discordUser['id'])->first();
            
            $users[] = [
                'id' => $discordUser['id'],
                'username' => $discordUser['username'] ?? '-',
                'discriminator' => $discordUser['discriminator'] ?? '',
                'role' => $dbUser->role ?? '-',
                'level' => $dbUser->level ?? '-',
                'xp' => $dbUser->userStat->xp ?? 0,
                'joined_at' => $dbUser->created_at ?? null,
                'has_account' => $dbUser ? true : false,
            ];
        }
        
        return view('admin.users', compact('users'));
    }

    /**
     * Display user details.
     */
    public function userDetails($id)
    {
        // Cari user di database berdasarkan discord_id
        $dbUser = User::with('userStat')->where('discord_id', $id)->first();
        
        // Ambil detail user dari bot Discord sebagai fallback
        $discordUser = null;
        try {
            $response = \Http::timeout(5)->get('http://localhost:3001/users/' . $id);
            if ($response->successful()) {
                $discordUser = $response->json('user') ?? null;
            }
        } catch (\Exception $e) {
            // Jika gagal, lanjutkan dengan data database saja
        }
        
        // Gabungkan data
        $user = [
            'id' => $id,
            'username' => $discordUser['username'] ?? $dbUser->discord_username ?? '-',
            'discriminator' => $discordUser['discriminator'] ?? $dbUser->discord_discriminator ?? '',
            'avatar' => $discordUser['avatar'] ?? $dbUser->discord_avatar ?? null,
            'joined_discord_at' => $discordUser['joined_at'] ?? null,
            'joined_website_at' => $dbUser->created_at ?? null,
            'role' => $dbUser->role ?? '-',
            'level' => $dbUser->level ?? '-',
            'xp' => $dbUser->userStat->xp ?? 0,
            'discord_roles' => $discordUser['roles'] ?? [],
            'guild' => $discordUser['guild'] ?? 'Unknown',
            'has_account' => $dbUser ? true : false,
        ];
        
        return view('admin.user-details', compact('user'));
    }

    /**
     * Display all game requests.
     */
    public function gameRequests()
    {
        $requests = GameRequest::with('user')->latest()->paginate(15);
        return view('admin.requests', compact('requests'));
    }

    /**
     * Update the status of a game request.
     */
    public function updateRequestStatus($id, Request $request)
    {
        $request->validate([
            'status' => 'required|in:reviewing,approved,rejected,completed',
            'admin_notes' => 'nullable|string',
        ]);
        
        $gameRequest = GameRequest::findOrFail($id);
        
        $gameRequest->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Status permintaan berhasil diperbarui.');
    }
    
    /**
     * Ban or unban a user.
     */
    public function toggleUserBan($id, Request $request)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'ban_reason' => 'required_if:is_banned,true|string',
            'banned_until' => 'nullable|date|after:now',
        ]);

        $user->update([
            'is_banned' => !$user->is_banned,
            'ban_reason' => $request->ban_reason,
            'banned_until' => $request->banned_until,
        ]);
        
        $status = $user->is_banned ? 'dibanned' : 'dibatalkan banned-nya';
        
        return redirect()->back()->with('success', "User {$user->name} berhasil {$status}.");
    }
}