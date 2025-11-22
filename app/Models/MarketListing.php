<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'item_id',
        'price_coins',
        'quantity',
        'status',
        'sold_at',
    ];

    protected $casts = [
        'sold_at' => 'datetime',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
