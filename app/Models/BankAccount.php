<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
        'interest_rate',
        'last_interest_at',
    ];

    protected $casts = [
        'last_interest_at' => 'datetime',
        'interest_rate' => 'decimal:4',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
