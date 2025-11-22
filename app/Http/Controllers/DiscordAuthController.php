<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Tambahkan ini
use Laravel\Socialite\Facades\Socialite;

class DiscordAuthController extends Controller
{
    /**
     * Redirect user to Discord authentication page.
     */
    public function redirect()
    {
        return Socialite::driver('discord')
            ->scopes(['identify', 'email', 'guilds'])
            ->redirect();
    }

    /**
     * Obtain user information from Discord and save to DB.
     */
    public function callback()
    {
        try {
            Log::info('Discord OAuth Callback Started');
            
            $discordUser = Socialite::driver('discord')->user();
            Log::info('Discord User Retrieved', ['discord_id' => $discordUser->getId()]);
            
            // Buat atau perbarui user di database
            $user = User::createOrUpdateDiscordUser($discordUser);
            Log::info('User Created/Updated', ['user_id' => $user->id, 'username' => $user->username]);
            
            // Store OAuth access token for later use (e.g., fetching guilds)
            // Store in session for now - in production, consider encrypting and storing in DB
            session(['discord_access_token' => $discordUser->token]);
            session(['discord_refresh_token' => $discordUser->refreshToken]);
            
            // Fetch Discord Roles
            try {
                $botToken = config('services.discord.bot_token');
                $guildId = config('services.discord.guild_id');
                
                if ($botToken && $guildId) {
                    $response = \Illuminate\Support\Facades\Http::withHeaders([
                        'Authorization' => 'Bot ' . $botToken,
                    ])->get("https://discord.com/api/v10/guilds/{$guildId}/members/{$user->discord_id}");
                    
                    if ($response->successful()) {
                        $memberData = $response->json();
                        $roleIds = $memberData['roles'] ?? [];
                        
                        // Fetch Guild Roles to get names and colors
                        $rolesResponse = \Illuminate\Support\Facades\Http::withHeaders([
                            'Authorization' => 'Bot ' . $botToken,
                        ])->get("https://discord.com/api/v10/guilds/{$guildId}/roles");
                        
                        if ($rolesResponse->successful()) {
                            $allRoles = collect($rolesResponse->json());
                            
                            $userRoles = $allRoles->filter(function ($role) use ($roleIds) {
                                return in_array($role['id'], $roleIds);
                            })->map(function ($role) {
                                return [
                                    'name' => $role['name'],
                                    'color' => $role['color'] ? '#' . dechex($role['color']) : '#99aab5',
                                    'position' => $role['position'],
                                ];
                            })->sortByDesc('position')->values()->all();
                            
                            $user->discord_roles = $userRoles;
                            $user->save();
                            Log::info('Discord Roles Fetched', ['roles_count' => count($userRoles)]);
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('Failed to fetch Discord roles: ' . $e->getMessage());
            }
            
            // Login user
            Auth::login($user, true);
            Log::info('User Logged In', ['auth_check' => Auth::check(), 'auth_id' => Auth::id()]);
            
            return redirect()->route('home')->with('success', 'Selamat datang di GameHub, ' . $user->username . '!');
        } catch (\Exception $e) {
            // TULIS ERROR KE LOG FILE
            Log::error('Discord OAuth Callback Error: ' . $e->getMessage());
            Log::error('Stack Trace: ' . $e->getTraceAsString());
            
            // Tetap tampilkan pesan error yang ramah ke user
            return redirect()->route('home')->with('error', 'Gagal masuk dengan Discord. Silakan coba lagi.');
        }
    }
    
    /**
     * Fetch user's Discord guilds using stored access token
     */
    public function getUserGuilds()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $accessToken = session('discord_access_token');
        
        if (!$accessToken) {
            return response()->json(['error' => 'No access token found. Please login again.'], 401);
        }
        
        try {
            // Fetch user's guilds from Discord API
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
            ])->get('https://discord.com/api/v10/users/@me/guilds');
            
            if (!$response->successful()) {
                return response()->json(['error' => 'Failed to fetch guilds from Discord'], 500);
            }
            
            $guilds = $response->json();
            
            // Filter guilds where user has MANAGE_GUILD or ADMINISTRATOR permission
            $filteredGuilds = collect($guilds)->filter(function ($guild) {
                $permissions = intval($guild['permissions'] ?? 0);
                $hasManageGuild = ($permissions & 0x00000020) === 0x00000020; // MANAGE_GUILD
                $isAdmin = ($permissions & 0x00000008) === 0x00000008; // ADMINISTRATOR
                
                return $guild['owner'] || $hasManageGuild || $isAdmin;
            })->map(function ($guild) {
                $iconUrl = null;
                if (isset($guild['icon']) && $guild['icon']) {
                    $iconUrl = "https://cdn.discordapp.com/icons/{$guild['id']}/{$guild['icon']}.png?size=128";
                }
                
                return [
                    'id' => $guild['id'],
                    'name' => $guild['name'],
                    'icon' => $iconUrl,
                    'is_owner' => $guild['owner'] ?? false,
                ];
            })->values()->all();
            
            return response()->json([
                'success' => true,
                'guilds' => $filteredGuilds
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to fetch user guilds: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch Discord servers',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Logout user
     */
    public function logout()
    {
        Auth::logout();
        
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        
        return redirect()->route('home')->with('success', 'Anda telah keluar.');
    }
}