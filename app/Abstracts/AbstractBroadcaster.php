<?php

namespace App\Abstracts;

use App\Models\Game\Table;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class AbstractBroadcaster implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected string $event = '';
    protected AbstractPokerTable $tableObj;

    public function __construct(
        int $tableId,
        public string      $screen,
        protected string   $channel
    ) {
        $this->tableObj = Table::find($tableId)->object;
    }

    public function broadcastOn()
    {
        return new Channel($this->channel);
    }

    public function broadcastAs()
    {
        return $this->event;
    }
}
