<?php

namespace App\Abstracts;

use App\Builders\PokerTableBuilder;
use App\Http\Resources\Game\Api\TableResource;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class AbstractGameBroadcaster extends AbstractBroadcaster
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected string $event = 'game';
    protected PokerTableBuilder $builder;

    public function __construct(
        public int    $tableId,
        public int    $userId
    )
    {
        $this->builder = new PokerTableBuilder($this->tableId, $this->userId);
    }

    public function broadcastAs()
    {
        return $this->event;
    }

    public function broadcastWith()
    {
        return TableResource::make($this->builder);
    }
}
