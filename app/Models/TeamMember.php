<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TeamMember extends Pivot
{
    use HasFactory;

    protected $table = 'team_members';

    protected $fillable = [
        'team_id',
        'user_id',
        'role',
        'joined_at',
    ];

    public $timestamps = true;
}
