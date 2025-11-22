<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamCompetitionScore extends Model
{
    use HasFactory;
    protected $fillable = ['team_competition_id', 'team_id', 'score'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function competition()
    {
        return $this->belongsTo(TeamCompetition::class, 'team_competition_id');
    }
}
