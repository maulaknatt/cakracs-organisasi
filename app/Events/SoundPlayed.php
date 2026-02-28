<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SoundPlayed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $url;
    public $channel_id;
    public $user_id;

    public function __construct($url, $channel_id, $user_id)
    {
        $this->url = $url;
        $this->channel_id = $channel_id;
        $this->user_id = $user_id;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('global-sound'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'sound-played';
    }
}
