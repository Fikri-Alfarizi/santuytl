<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'rules', 'starts_at', 'ends_at', 'type', 'tier', 'min_level', 'access_level', 'xp_reward', 'coin_reward', 'badge_reward', 'role_reward', 'vip_days_reward', 'auto_reward', 'is_active', 'is_featured', 'banner_image',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function participants()
    {
        return $this->hasMany(EventParticipant::class);
    }
}
