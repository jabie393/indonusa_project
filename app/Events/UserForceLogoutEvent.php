<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserForceLogoutEvent implements ShouldBroadcast
{
    public string $sessionId;

    public function __construct($sessionId)
    {
        $this->sessionId = $sessionId;
    }

    public function broadcastOn()
    {
        return new Channel('session-'.$this->sessionId);
    }

    public function broadcastAs()
    {
        return 'force-logout';
    }
}
