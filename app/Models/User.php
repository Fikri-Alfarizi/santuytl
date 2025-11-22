<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Check if user has a Discord role (case-insensitive, supports array or string)
     */
    public function hasDiscordRole($role)
    {
        if (empty($this->discord_roles) || !is_array($this->discord_roles)) {
            return false;
        }
        // Case-insensitive match
        foreach ($this->discord_roles as $userRole) {
            if (strcasecmp($userRole, $role) === 0) {
                return true;
            }
        }
        return false;
    }

    // Relasi badges untuk fitur lencana
    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')->withTimestamps();
    }

    protected $fillable = [
        'username',
        'email',
        'password', // Diisi null karena login via Discord
        'discord_id',
        'discord_username',
        'discord_discriminator',
        'discord_avatar',
        'role',
        'level',
        'is_banned',
        'ban_reason',
        'banned_until',
        'vip_expires_at',
        'discord_roles',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'banned_until' => 'datetime',
            'vip_expires_at' => 'datetime',
            'is_banned' => 'boolean',
            'discord_roles' => 'array',
        ];
    }
    
    /**
     * Relasi ke tabel user_stats
     */
    // For compatibility: allow both $user->stats and $user->stats()
    public function stats()
    {
        return $this->hasOne(UserStat::class);
    }

    // Legacy: keep for backward compatibility
    public function userStat()
    {
        return $this->hasOne(UserStat::class);
    }

    /**
     * Check if user is VIP
     */
    public function isVip()
    {
        return $this->role === 'vip' || ($this->vip_expires_at && $this->vip_expires_at > now());
    }
    
    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    
    /**
     * Check if user is moderator
     */
    public function isModerator()
    {
        return $this->role === 'moderator' || $this->isAdmin();
    }
    
    /**
     * Check if user is staff (admin, moderator, or owner)
     */
    public function isStaff()
    {
        return in_array($this->role, ['admin', 'moderator', 'owner']);
    }
    
    /**
     * Get Discord avatar URL
     */
    public function getDiscordAvatarUrlAttribute()
    {
        if (!$this->discord_avatar) {
            return 'https://cdn.discordapp.com/embed/avatars/0.png';
        }
        
        // Jika sudah full URL (misal dari avatar user yang disimpan langsung)
        if (str_starts_with($this->discord_avatar, 'http')) {
            return $this->discord_avatar;
        }
        
        $format = str_starts_with($this->discord_avatar, 'a_') ? 'gif' : 'png';
        return "https://cdn.discordapp.com/avatars/{$this->discord_id}/{$this->discord_avatar}.{$format}?size=256";
    }
    
    /**
     * Create or update a user from Discord OAuth
     * Method ini yang akan kita gunakan untuk menyimpan user
     */
    public static function createOrUpdateDiscordUser($providerUser)
    {
        // Cari user berdasarkan discord_id
        $user = self::where('discord_id', $providerUser->getId())->first();
        
        // Jika tidak ditemukan, buat user baru
        if (!$user) {
            $user = new self();
            $user->discord_id = $providerUser->getId();
            $user->email = $providerUser->getEmail();
            $user->username = $providerUser->getName();
            $user->role = 'member'; // Role default
            $user->level = 'warga_baru'; // Level default
            // Set password random (hash)
            $user->password = bcrypt(uniqid('discord_', true));
        }
        
        // Perbarui data Discord terlepas user baru atau lama
        $user->discord_username = $providerUser->getNickname();
        $user->discord_discriminator = $providerUser->user['discriminator'] ?? null;
        // Ambil hash avatar dari data Discord, fallback ke null jika tidak ada
        $user->discord_avatar = $providerUser->avatar ?? $providerUser->getAvatar() ?? null;
        
        $user->save();
        
        // Buat catatan statistik awal jika belum ada
        // Ini penting agar dashboard tidak error
        if (!$user->userStat) {
            $user->userStat()->create([
                'xp' => 0,
                'level' => 1,
                'xp_to_next_level' => 100,
                'messages_count' => 0,
                'games_downloaded' => 0,
                'requests_made' => 0,
                'events_participated' => 0,
                'events_won' => 0,
                'last_active_at' => now(),
            ]);
        }
        
        return $user;
    }
    
    /**
     * Cek apakah user adalah owner
     */
    public function isOwner()
    {
        return $this->role === 'owner';
    }

    /**
     * Cek apakah user adalah admin atau owner
     */
    public function isAdminOrOwner()
    {
        return in_array($this->role, ['admin', 'owner']);
    }
}