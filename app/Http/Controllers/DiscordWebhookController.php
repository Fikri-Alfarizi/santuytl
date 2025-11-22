<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Events\XpUpdated;

class DiscordWebhookController extends Controller
{
    /**
     * Handle incoming webhook from Discord bot.
     */
    public function handle(Request $request)
    {
        // 1. Validasi Secret Key untuk Keamanan
        $secret = $request->header('X-Discord-Bot-Secret');
        if ($secret !== env('DISCORD_BOT_SECRET')) {
            Log::warning('Webhook called with invalid secret.');
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // 2. Ambil data JSON dari request
        $data = $request->json();

        // 3. Pastikan data yang diperlukan ada
        if (!isset($data['discord_id']) || !isset($data['event_type'])) {
            Log::error('Webhook received with missing data.', ['data' => $data]);
            return response()->json(['error' => 'Bad Request'], 400);
        }

        // 4. Cari user berdasarkan discord_id
        $user = User::where('discord_id', $data['discord_id'])->first();
        if (!$user) {
            Log::warning('Webhook received for unknown user.', ['discord_id' => $data['discord_id']]);
            return response()->json(['error' => 'User Not Found'], 404);
        }

        // 5. Proses event berdasarkan jenisnya
        switch ($data['event_type']) {
            case 'user_activity':
                $this->handleUserActivity($user, $data);
                break;

            case 'xp_update':
                $this->handleXpUpdate($user, $data);
                break;

            case 'role_update':
                $this->handleRoleUpdate($user, $data);
                break;
            
            default:
                Log::info('Webhook received with unknown event type.', ['event_type' => $data['event_type']]);
                break;
        }

        // 6. Berikan respons sukses
        return response()->json(['status' => 'success']);
    }

    /**
     * Handle user activity (update last active timestamp)
     */
    private function handleUserActivity(User $user, array $data)
    {
        // Update last_active_at di tabel user_stats
        UserStat::where('user_id', $user->id)->update(['last_active_at' => now()]);
        Log::info("User activity updated for {$user->name}");
    }

    /**
     * Handle XP update and level up logic
     */
    private function handleXpUpdate(User $user, array $data)
    {
        $xpToAdd = $data['xp'] ?? 0;
        if ($xpToAdd <= 0) return;

        // Ambil statistik user saat ini
        $stats = UserStat::firstOrCreate(
            ['user_id' => $user->id],
            ['xp' => 0, 'level' => 1, 'xp_to_next_level' => 100]
        );

        $oldLevel = $stats->level;
        $newXp = $stats->xp + $xpToAdd;

        // Tentukan level baru berdasarkan XP
        $newLevel = $this->calculateLevel($newXp);

        // Update XP dan level
        $stats->update([
            'xp' => $newXp,
            'level' => $newLevel,
            'xp_to_next_level' => $this->getXpForNextLevel($newLevel)
        ]);

        // Jika level naik, update role di tabel users
        if ($newLevel > $oldLevel) {
            $newRole = $this->getRoleByLevel($newLevel);
            $user->update(['role' => $newRole]);
            Log::info("User {$user->name} leveled up to {$newLevel} and got new role: {$newRole}");

            // Award badge otomatis saat mencapai level tertentu
            if ($newLevel == 5) {
                $badge = \App\Models\Badge::where('slug', 'sepuh')->first();
                if ($badge && !$user->badges->contains($badge->id)) {
                    $user->badges()->attach($badge->id, ['earned_at' => now()]);
                    Log::info("Badge 'Sepuh' awarded to {$user->name}");
                }
            }
        }

        Log::info("XP updated for {$user->name}", ['xp_added' => $xpToAdd, 'new_total_xp' => $newXp, 'new_level' => $newLevel]);

        // Broadcast event real-time ke frontend
        broadcast(new XpUpdated($user, $xpToAdd, $newXp, $newLevel, $this->getXpForNextLevel($newLevel)))->toOthers();
    }
    
    /**
     * Handle role update
     */
    private function handleRoleUpdate(User $user, array $data)
    {
        $newRole = $data['new_role'] ?? null;
        $allRoles = $data['all_roles'] ?? null;
        Log::debug('Webhook role_update event received', ['discord_id' => $user->discord_id, 'new_role' => $newRole, 'all_roles' => $allRoles]);
        $update = [];
        if ($newRole) {
            $update['discord_role'] = $newRole;
        }
        if ($allRoles) {
            $update['discord_roles'] = json_encode($allRoles);
        }
        if ($update) {
            $user->update($update);
            Log::info("Discord role(s) updated for {$user->name}", $update);
            Log::debug('User after update', $user->fresh()->toArray());
        }
    }

    /**
     * Fungsi untuk menghitung level berdasarkan XP
     */
    private function calculateLevel(int $xp): int
    {
        // Contoh logika: setiap 100 XP naik 1 level
        // Anda bisa membuat rumus yang lebih kompleks
        return intval($xp / 100) + 1;
    }

    /**
     * Fungsi untuk mendapatkan XP yang dibutuhkan untuk level berikutnya
     */
    private function getXpForNextLevel(int $currentLevel): int
    {
        // Jika level saat ini adalah level tertinggi, tidak ada level berikutnya
        if ($currentLevel >= 5) { // Anggap level 5 (Sepuh) adalah tertinggi
            return 0;
        }
        return ($currentLevel * 100); // Contoh: Level 2 butuh 200 XP, level 3 butuh 300 XP
    }

    /**
     * Fungsi untuk menentukan role berdasarkan level
     */
    private function getRoleByLevel(int $level): string
    {
        if ($level >= 5) return 'sepuh';
        if ($level >= 4) return 'suhu';
        if ($level >= 3) return 'belajar_pro';
        if ($level >= 2) return 'pemula';
        
        return 'warga_baru';
    }
}