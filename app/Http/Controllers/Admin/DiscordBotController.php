<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DiscordBotController extends Controller
{
    /**
     * Get selected guild ID from session or fallback to config/API
     */
    private function getGuildId()
    {
        // Priority 1: Session (user selected server)
        $guildId = session('selected_guild_id');
        
        // Priority 2: Config
        if (!$guildId) {
            $guildId = config('services.discord.guild_id');
        }
        
        // Priority 3: Fetch from bot API
        if (!$guildId) {
            try {
                $response = \Http::get('http://localhost:3001/guilds');
                if ($response->successful()) {
                    $guilds = $response->json('guilds') ?? [];
                    if (!empty($guilds)) {
                        $guildId = $guilds[0]['id'] ?? null;
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Failed to fetch guild_id: ' . $e->getMessage());
            }
        }
        
        return $guildId;
    }

    /**
     * Show server selection page
     */
    public function selectServer()
    {
        return view('admin.select-server');
    }

    /**
     * Set selected server in session
     */
    public function setServer(Request $request)
    {
        $request->validate([
            'guild_id' => 'required|string'
        ]);

        // Fetch guild name from bot API
        try {
            $response = \Http::get('http://localhost:3001/guilds');
            if ($response->successful()) {
                $guilds = $response->json('guilds') ?? [];
                $selectedGuild = collect($guilds)->firstWhere('id', $request->guild_id);
                
                if ($selectedGuild) {
                    session([
                        'selected_guild_id' => $request->guild_id,
                        'selected_guild_name' => $selectedGuild['name']
                    ]);
                    
                    return redirect()->route('admin.discord.status')
                        ->with('success', 'Server berhasil dipilih: ' . $selectedGuild['name']);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Failed to set server: ' . $e->getMessage());
        }

        return redirect()->back()->with('error', 'Gagal memilih server');
    }

    
    public function showSendMessageForm()
    {
        $channels = [];
        try {
            $response = Http::get('http://localhost:3001/channels');
            if ($response->successful()) {
                $channels = $response->json('channels') ?? [];
            }
        } catch (\Exception $e) {
        }
        return view('admin.send-discord-message', compact('channels'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'channel_id' => 'required|string',
            'message' => 'required|string',
        ]);

        $response = Http::post('http://localhost:3001/send-message', [
            'channel_id' => $request->channel_id,
            'message' => $request->message,
        ]);

        return $response->successful()
            ? back()->with('success', 'Pesan berhasil dikirim ke Discord!')
            : back()->with('error', 'Gagal mengirim pesan ke Discord.');
    }

    public function showSendDmForm()
    {
        $users = [];
        try {
            $response = Http::get('http://localhost:3001/users');
            if ($response->successful()) {
                $users = $response->json('users') ?? [];
            }
        } catch (\Exception $e) {
        }
        return view('admin.send-discord-dm', compact('users'));
    }
    
    public function sendDm(Request $request)
    {
        $request->validate([
            'user_id' => 'required|string',
            'message' => 'required|string',
        ]);
        
        $response = Http::post('http://localhost:3001/send-dm', [
            'user_id' => $request->user_id,
            'message' => $request->message,
        ]);
        
        return $response->successful()
            ? back()->with('success', 'DM berhasil dikirim!')
            : back()->with('error', 'Gagal mengirim DM.');
    }

    public function showKickForm()
    {
        $users = [];
        try {
            $response = \Http::get('http://localhost:3001/users');
            if ($response->successful()) {
                $users = $response->json('users') ?? [];
            }
        } catch (\Exception $e) {
        }
        return view('admin.kick-discord-user', compact('users'));
    }
    
    public function kickUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|string',
            'reason' => 'nullable|string',
        ]);
        
        $response = Http::post('http://localhost:3001/kick', [
            'guild_id' => $this->getGuildId(),
            'user_id' => $request->user_id,
            'reason' => $request->reason,
        ]);
        
        return $response->successful()
            ? back()->with('success', 'User berhasil di-kick!')
            : back()->with('error', 'Gagal kick user: ' . $response->body());
    }

    public function showBanForm()
    {
        $users = [];
        try {
            $response = \Http::get('http://localhost:3001/users');
            if ($response->successful()) {
                $users = $response->json('users') ?? [];
            }
        } catch (\Exception $e) {
        }
        return view('admin.ban-discord-user', compact('users'));
    }
    
    public function banUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|string',
            'reason' => 'nullable|string',
        ]);
        
        $response = Http::post('http://localhost:3001/ban', [
            'guild_id' => $this->getGuildId(),
            'user_id' => $request->user_id,
            'reason' => $request->reason,
        ]);
        
        return $response->successful()
            ? back()->with('success', 'User berhasil di-ban!')
            : back()->with('error', 'Gagal ban user: ' . $response->body());
    }

    public function showAssignRoleForm()
    {
        $users = [];
        $roles = [];
        try {
            $userRes = \Http::get('http://localhost:3001/users');
            if ($userRes->successful()) {
                $users = $userRes->json('users') ?? [];
            }
            $roleRes = \Http::get('http://localhost:3001/roles');
            if ($roleRes->successful()) {
                $roles = $roleRes->json('roles') ?? [];
            }
        } catch (\Exception $e) {}
        return view('admin.assign-discord-role', compact('users', 'roles'));
    }
    
    public function assignRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|string',
            'role_id' => 'required|string',
        ]);
        
        $response = Http::post('http://localhost:3001/assign-role', [
            'guild_id' => $this->getGuildId(),
            'user_id' => $request->user_id,
            'role_id' => $request->role_id,
        ]);
        
        return $response->successful()
            ? back()->with('success', 'Role berhasil diberikan!')
            : back()->with('error', 'Gagal assign role: ' . $response->body());
    }

    public function showRemoveRoleForm()
    {
        $users = [];
        $roles = [];
        try {
            $userRes = \Http::get('http://localhost:3001/users');
            if ($userRes->successful()) {
                $users = $userRes->json('users') ?? [];
            }
            $roleRes = \Http::get('http://localhost:3001/roles');
            if ($roleRes->successful()) {
                $roles = $roleRes->json('roles') ?? [];
            }
        } catch (\Exception $e) {}
        return view('admin.remove-discord-role', compact('users', 'roles'));
    }
    
    public function removeRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|string',
            'role_id' => 'required|string',
        ]);
        
        $response = Http::post('http://localhost:3001/remove-role', [
            'guild_id' => $this->getGuildId(),
            'user_id' => $request->user_id,
            'role_id' => $request->role_id,
        ]);
        
        return $response->successful()
            ? back()->with('success', 'Role berhasil dihapus!')
            : back()->with('error', 'Gagal remove role: ' . $response->body());
    }

    public function botStatus()
    {
        try {
            $response = Http::get('http://localhost:3001/status');
            if ($response->successful()) {
                $data = $response->json();
                return view('admin.bot-status', ['status' => $data]);
            } else {
                return view('admin.bot-status', ['status' => ['online' => false]]);
            }
        } catch (\Exception $e) {
            return view('admin.bot-status', ['status' => ['online' => false]]);
        }
    }
    
    // VOICE CHANNEL CONTROL
    public function showVoiceForm()
    {
        $channels = [];
        try {
            $response = Http::get('http://localhost:3001/voice/channels');
            if ($response->successful()) {
                $channels = $response->json('channels') ?? [];
            }
        } catch (\Exception $e) {
        }
        
        $voiceStatus = null;
        try {
            $response = Http::get('http://localhost:3001/voice/status');
            if ($response->successful()) {
                $voiceStatus = $response->json();
            }
        } catch (\Exception $e) {
        }
        
        return view('admin.voice-control', compact('channels', 'voiceStatus'));
    }
    
    public function joinVoice(Request $request)
    {
        $request->validate([
            'channel_id' => 'required|string',
        ]);
        
        $response = Http::post('http://localhost:3001/voice/join', [
            'guild_id' => $this->getGuildId(),
            'channel_id' => $request->channel_id,
        ]);
        
        return $response->successful()
            ? back()->with('success', 'Bot berhasil join voice channel!')
            : back()->with('error', 'Gagal join voice: ' . $response->body());
    }
    
    public function leaveVoice()
    {
        $response = Http::post('http://localhost:3001/voice/leave');
        
        return $response->successful()
            ? back()->with('success', 'Bot berhasil leave voice channel!')
            : back()->with('error', 'Gagal leave voice: ' . $response->body());
    }
    
    public function voiceStatus()
    {
        try {
            $response = Http::get('http://localhost:3001/voice/status');
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['connected' => false, 'error' => $e->getMessage()]);
        }
    }
}
