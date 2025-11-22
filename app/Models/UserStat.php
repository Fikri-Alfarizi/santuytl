<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'xp', 'level', 'prestige_level', 'xp_to_next_level', 'coins', 
        'messages_count', 'games_downloaded', 'requests_made', 'events_participated', 
        'events_won', 'last_active_at', 'job_id', 'total_prestiges', 'last_prestige_at',
        'weekly_xp',
    ];

    protected $casts = [
        'last_active_at' => 'datetime',
        'last_prestige_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Job
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Relasi ke Prestige History
     */
    public function prestigeHistory()
    {
        return $this->hasMany(PrestigeHistory::class, 'user_id', 'user_id');
    }

    /**
     * Cek apakah user bisa melakukan prestige
     * Syarat: Level harus mencapai level maksimum (misal 100)
     */
    public function canPrestige()
    {
        $maxLevel = config('gamification.max_level', 100);
        return $this->level >= $maxLevel;
    }

    /**
     * Melakukan prestige
     * Reset level ke 1, XP ke 0, tapi prestige_level naik
     */
    public function doPrestige()
    {
        if (!$this->canPrestige()) {
            return false;
        }

        // Simpan history
        PrestigeHistory::create([
            'user_id' => $this->user_id,
            'prestige_level' => $this->prestige_level + 1,
            'old_level' => $this->level,
            'old_xp' => $this->xp,
            'prestiged_at' => now(),
        ]);

        // Reset stats
        $this->level = 1;
        $this->xp = 0;
        $this->xp_to_next_level = 100;
        $this->prestige_level += 1;
        $this->total_prestiges += 1;
        $this->last_prestige_at = now();
        $this->save();

        // Award prestige badge (akan diimplementasi nanti)
        $this->awardPrestigeBadge();

        return true;
    }

    /**
     * Apply job bonus ke XP yang didapat
     * 
     * @param int $baseXp XP dasar
     * @param string $xpType Tipe XP (voice, message, event)
     * @return int XP setelah bonus
     */
    public function applyJobBonus($baseXp, $xpType = 'all')
    {
        if (!$this->job) {
            return $baseXp;
        }

        return $this->job->applyBonus($baseXp, $xpType);
    }

    /**
     * Award prestige badge
     */
    private function awardPrestigeBadge()
    {
        // Cari badge prestige berdasarkan level
        $badgeSlug = 'prestige-' . $this->prestige_level;
        $badge = Badge::where('slug', $badgeSlug)->first();

        if ($badge && $this->user) {
            // Cek apakah user sudah punya badge ini
            if (!$this->user->badges()->where('badge_id', $badge->id)->exists()) {
                $this->user->badges()->attach($badge->id);
            }
        }
    }
}
