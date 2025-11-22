<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'leader_id',
        'total_xp',
    ];

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'team_members')
                    ->withPivot('role', 'joined_at')
                    ->withTimestamps();
    }

    public function competitionParticipants()
    {
        return $this->hasMany(CompetitionParticipant::class);
    }
}
