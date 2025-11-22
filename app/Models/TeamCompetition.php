<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamCompetition extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'start_at', 'end_at'];

    public function scores()
    {
        return $this->hasMany(TeamCompetitionScore::class);
    }
}
