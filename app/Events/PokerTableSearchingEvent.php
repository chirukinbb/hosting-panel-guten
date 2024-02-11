<?php

namespace App\Events;

use App\Abstracts\AbstractPokerTable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PokerTableSearchingEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private AbstractPokerTable $table;

    public function __construct(string $className, private int $currentCount,private int $userId)
    {
        $this->table = new $className;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.'.$this->userId);
    }

    public function broadcastAs()
    {
        return 'searching';
    }

    public function broadcastWith()
    {
        return [
            'type'=>$this->table->getType(),
            'current_count'=>$this->currentCount,
            'minimum_count'=>$this->table->getPlayersCount(),
        ];
    }
}
