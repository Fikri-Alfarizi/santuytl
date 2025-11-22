<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use App\Models\User;

class XpUpdated implements ShouldBroadcast
{
    use Dispatchable;

    public $user, $xpAmount, $newTotalXp, $newLevel, $xpToNextLevel;

    public function __construct(User $user, int $xpAmount, int $newTotalXp, int $newLevel, int $xpToNextLevel)
    {
        $this->user = $user;
        $this->xpAmount = $xpAmount;
        $this->newTotalXp = $newTotalXp;
        $this->newLevel = $newLevel;
        $this->xpToNextLevel = $xpToNextLevel;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->user->id);
    }

    public function broadcastAs()
    {
        return 'xp.updated';
    }
}
