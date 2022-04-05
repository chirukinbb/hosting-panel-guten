<?php

namespace App\Events\Game\Broadcasters;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlayersUpdateInPokerTableBroadcaster implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $count,
        public string $screen,
        protected string $channel
    ) {}

    public function broadcastOn()
    {
        return new PrivateChannel($this->channel);
    }

    public function broadcastAs()
    {
        return 'turn';
    }
}
