<?php

namespace App\Abstracts;

use App\Builders\PokerTableBuilder;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class AbstractBroadcaster implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected string $event = 'table';
    protected PokerTableBuilder $builder;

    public function __construct(
        public int    $tableId,
        public string $screen,
        public string $channel,
        public int    $userId
    )
    {
        $this->builder = new PokerTableBuilder($this->tableId, $this->userId);
    }

    public function broadcastOn()
    {
        return new PrivateChannel($this->channel);
    }

    public function broadcastAs()
    {
        return $this->event;
    }

    public function broadcastWith()
    {
        $table  = $this->action()
            ->getTable();

        return [
            'table' =>$table,
            'screen'=>$this->screen
        ];
    }

    abstract public function action(): PokerTableBuilder;
}
