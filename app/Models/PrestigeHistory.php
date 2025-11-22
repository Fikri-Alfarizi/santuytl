<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrestigeHistory extends Model
{
    use HasFactory;

    protected $table = 'prestige_history';

    protected $fillable = [
        'user_id',
        'prestige_level',
        'old_level',
        'old_xp',
        'prestiged_at',
    ];

    protected $casts = [
        'prestiged_at' => 'datetime',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
