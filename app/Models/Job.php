<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'passive_skill_name',
        'passive_skill_description',
        'bonus_type',
        'bonus_percentage',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke UserStat
     */
    public function userStats()
    {
        return $this->hasMany(UserStat::class);
    }

    /**
     * Hitung bonus XP berdasarkan passive skill
     * 
     * @param int $baseXp XP dasar yang didapat
     * @param string $xpType Tipe XP (voice, message, event)
     * @return int XP setelah bonus
     */
    public function applyBonus($baseXp, $xpType = 'all')
    {
        // Jika bonus_type adalah 'xp_all', apply ke semua tipe
        if ($this->bonus_type === 'xp_all') {
            return $baseXp + ($baseXp * $this->bonus_percentage / 100);
        }

        // Jika bonus_type sesuai dengan xpType, apply bonus
        if ($this->bonus_type === 'xp_' . $xpType) {
            return $baseXp + ($baseXp * $this->bonus_percentage / 100);
        }

        // Tidak ada bonus
        return $baseXp;
    }

    /**
     * Get active jobs only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
