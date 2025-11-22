<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'rarity',
        'gacha_chance',
        'price_coins',
        'duration_days',
        'boost_percentage',
        'image',
        'is_tradeable',
        'is_active',
    ];

    protected $casts = [
        'is_tradeable' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeTradeable($query)
    {
        return $query->where('is_tradeable', true);
    }
}
