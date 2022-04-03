<?php

namespace App\Events\Game\Broadcasters;

use App\Abstracts\AbstractBroadcaster;

class StartPokerRoundBroadcaster extends AbstractBroadcaster
{
    public string $newEvent = 'table';
    protected string $event = 'table';

    public function __construct(int $tableId, string $screen, string $channel)
    {
        parent::__construct($tableId, $screen, $channel);

        $this->table = $this->repository->startRound()
            ->save()
            ->getTable();

        self::broadcast(new AuctionPokerRoundBroadcaster($tableId,$screen,$channel));
    }
}
