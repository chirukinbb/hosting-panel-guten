<?php

namespace App\Events\Game\Broadcasters;

use App\Abstracts\AbstractBroadcaster;

class EndTimeOnTurnBroadcaster extends AbstractBroadcaster
{
    protected string $event = 'turn';

    public function __construct(int $tableId, string $screen, string $channel)
    {
        parent::__construct($tableId, $screen, $channel);

        $this->table = $this->repository->entTimeForTurn()
            ->save()
            ->getTable();

        $this->table = $this->repository->getTable();

        if ($this->repository->isTurnTransfer())
            self::broadcast(new AuctionPokerRoundBroadcaster($tableId,$screen,$channel));

        if ($this->repository->isNewRound())
            self::broadcast(new StartPokerRoundBroadcaster($tableId,$screen,$channel));

        if ($this->repository->isTableFinish())
            self::broadcast(new GameOverBroadcaster($tableId,'list',$channel));
    }
}
