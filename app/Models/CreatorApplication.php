<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreatorApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'platform', 'channel_url', 'subscriber_count', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
