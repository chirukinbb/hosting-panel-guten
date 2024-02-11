<?php

namespace App\Events\Game;

use App\Abstracts\AbstractBroadcaster;
use App\Models\Game\Table;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StartPokerRoundEvent extends AbstractBroadcaster
{
    protected string $event = 'table';

    public function __construct(int $tableId, string $screen, string $channel)
    {
        parent::__construct($tableId, $screen, $channel);

        $this->table = $this->repository->startRound()
            ->save()
            ->getTable();

        self::broadcast(new StartPokerRoundEvent($tableId,$screen,$channel));
    }
}
