<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'sender_items',
        'receiver_items',
        'sender_coins',
        'receiver_coins',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'sender_items' => 'array',
        'receiver_items' => 'array',
        'completed_at' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
