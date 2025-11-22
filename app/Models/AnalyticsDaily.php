<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalyticsDaily extends Model
{
    use HasFactory;

    protected $table = 'analytics_daily';

    protected $fillable = [
        'date', 
        'active_users', 
        'new_users', 
        'total_messages', 
        'total_voice_minutes'
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
