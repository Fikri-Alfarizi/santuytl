<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GameHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'game_type',
        'result',
        'reward',
        'played_at',
    ];

    protected $casts = [
        'result' => 'array',
        'reward' => 'array',
        'played_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
