<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'participant_id', 'voter_id',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function participant()
    {
        return $this->belongsTo(EventParticipant::class, 'participant_id');
    }

    public function voter()
    {
        return $this->belongsTo(User::class, 'voter_id');
    }
}
