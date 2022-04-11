<?php

namespace App\Abstracts;

use App\Builders\PokerTableBuilder;
use App\Repositories\PokerTableRepository;
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
    protected string $broadcasterClassName;

    public function __construct(
        public int    $tableId,
        public string $screen,
        public string $channel,
        public int    $userId
    )
    {
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
        $this->builder = new PokerTableBuilder($this->tableId, $this->userId);

        $table  = $this->action()
            ->getTable();

        if (class_exists($this->broadcasterClassName)) {
            broadcast(new $this->broadcasterClassName(
                $this->tableId,
                $this->screen,
                $this->channel,
                $this->userId
            ));
        }

        return [
            'table' =>$table,
            'screen'=>$this->screen
        ];
    }

    abstract public function action(): PokerTableBuilder;
}
