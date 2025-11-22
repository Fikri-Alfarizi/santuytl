<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VipPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'days', 'amount', 'currency', 'payment_method', 'payment_reference', 'status', 'paid_at', 'expires_at', 'admin_notes', 'processed_by',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
