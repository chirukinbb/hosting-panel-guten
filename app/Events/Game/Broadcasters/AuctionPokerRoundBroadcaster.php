<?php

namespace App\Events\Game\Broadcasters;

use App\Abstracts\AbstractBroadcaster;

class AuctionPokerRoundBroadcaster extends AbstractBroadcaster
{
    protected string $event = 'table';

    public function __construct(int $tableId, string $screen, string $channel)
    {
        parent::__construct($tableId, $screen, $channel);

        $this->table = $this->repository->playerTurn()
            ->save()
            ->getTable();

        dispatch(new EndTimeOnTurnBroadcaster($tableId,$screen,$channel))->delay(now()->addSeconds(30));
    }
}
