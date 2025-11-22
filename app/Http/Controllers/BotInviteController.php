<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BotInviteController extends Controller
{
    /**
     * Fetch user's Discord guilds (servers)
     * Only returns guilds where user has permission to add bots
     */
    public function getUserGuilds(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        
        try {
            // Get user's access token from session or refresh it
            // For now, we'll use the bot token to check guild membership
            $botToken = config('services.discord.bot_token');
            $guildId = config('services.discord.guild_id');
            
            // Fetch user's guilds using Discord API
            // Note: This requires the user's OAuth token with 'guilds' scope
            // Since we're using Socialite, we need to store the access token
            
            // For this implementation, we'll fetch guilds the user can manage
            // This is a simplified version - in production, you'd store the OAuth token
            
            $guilds = $this->fetchUserGuildsFromDiscord($user);
            
            // Filter guilds based on permissions
            $filteredGuilds = $this->filterGuildsByPermissions($guilds);
            
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
     * Generate bot invite URL for a specific guild
     */
    public function generateInviteUrl(Request $request)
    {
        $request->validate([
            'guild_id' => 'required|string'
        ]);
        
        $clientId = config('services.discord.client_id');
        $permissions = config('services.discord.bot_permissions', '8'); // Default: Administrator
        $guildId = $request->input('guild_id');
        
        // Generate Discord bot invite URL
        $inviteUrl = "https://discord.com/api/oauth2/authorize?" . http_build_query([
            'client_id' => $clientId,
            'permissions' => $permissions,
            'scope' => 'bot applications.commands',
            'guild_id' => $guildId,
            'disable_guild_select' => 'true'
        ]);
        
        return response()->json([
            'success' => true,
            'invite_url' => $inviteUrl
        ]);
    }
    
    /**
     * Check if bot is already in a guild
     */
    public function checkBotInGuild(Request $request)
    {
        $request->validate([
            'guild_id' => 'required|string'
        ]);
        
        $botToken = config('services.discord.bot_token');
        $guildId = $request->input('guild_id');
        
        try {
            // Check if bot is in the guild by trying to fetch guild info
            $response = Http::withHeaders([
                'Authorization' => 'Bot ' . $botToken,
            ])->get("https://discord.com/api/v10/guilds/{$guildId}");
            
            return response()->json([
                'success' => true,
                'is_in_guild' => $response->successful()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'is_in_guild' => false
            ]);
        }
    }
    
    /**
     * Fetch user's guilds from Discord API
     * Note: This is a placeholder - you need to store OAuth access token
     */
    private function fetchUserGuildsFromDiscord($user)
    {
        // In a real implementation, you would:
        // 1. Store the OAuth access token when user logs in
        // 2. Use that token to fetch guilds
        
        // For now, we'll return the guild the user is in
        $botToken = config('services.discord.bot_token');
        $guildId = config('services.discord.guild_id');
        
        try {
            // Check if user is in the main guild
            $response = Http::withHeaders([
                'Authorization' => 'Bot ' . $botToken,
            ])->get("https://discord.com/api/v10/guilds/{$guildId}/members/{$user->discord_id}");
            
            if ($response->successful()) {
                // Fetch guild info
                $guildResponse = Http::withHeaders([
                    'Authorization' => 'Bot ' . $botToken,
                ])->get("https://discord.com/api/v10/guilds/{$guildId}");
                
                if ($guildResponse->successful()) {
                    $guildData = $guildResponse->json();
                    return [[
                        'id' => $guildData['id'],
                        'name' => $guildData['name'],
                        'icon' => $guildData['icon'],
                        'owner' => $guildData['owner_id'] === $user->discord_id,
                        'permissions' => '0', // We'll calculate this based on roles
                    ]];
                }
            }
            
            return [];
            
        } catch (\Exception $e) {
            Log::error('Failed to fetch guilds: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Filter guilds based on user permissions
     * Only return guilds where user can manage server or is owner
     */
    private function filterGuildsByPermissions($guilds)
    {
        $allowedGuildIds = config('services.discord.allowed_guild_ids', []);
        
        return collect($guilds)->filter(function ($guild) use ($allowedGuildIds) {
            // If allowed_guild_ids is set, only return those guilds
            if (!empty($allowedGuildIds) && !in_array($guild['id'], $allowedGuildIds)) {
                return false;
            }
            
            // Check if user is owner
            if (isset($guild['owner']) && $guild['owner']) {
                return true;
            }
            
            // Check if user has MANAGE_GUILD permission (0x00000020)
            if (isset($guild['permissions'])) {
                $permissions = intval($guild['permissions']);
                $hasManageGuild = ($permissions & 0x00000020) === 0x00000020;
                $isAdmin = ($permissions & 0x00000008) === 0x00000008;
                
                return $hasManageGuild || $isAdmin;
            }
            
            return false;
        })->map(function ($guild) {
            // Format guild data for frontend
            $iconUrl = null;
            if ($guild['icon']) {
                $iconUrl = "https://cdn.discordapp.com/icons/{$guild['id']}/{$guild['icon']}.png?size=128";
            }
            
            return [
                'id' => $guild['id'],
                'name' => $guild['name'],
                'icon' => $iconUrl,
                'is_owner' => $guild['owner'] ?? false,
            ];
        })->values()->all();
    }
}
