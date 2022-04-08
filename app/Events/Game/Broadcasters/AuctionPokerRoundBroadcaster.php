<?php

namespace App\Events\Game\Broadcasters;

use App\Abstracts\AbstractBroadcaster;
use App\Jobs\Game\FinishPlayerTurnJob;
use App\Repositories\PokerTableRepository;

class AuctionPokerRoundBroadcaster extends AbstractBroadcaster
{
    protected string $broadcasterClassName = '';

    public function actions():PokerTableRepository
    {
        dispatch(new FinishPlayerTurnJob(
            $this->tableId,
            $this->userId
        ))->delay(now()->addSeconds(21));

        return $this->repository->startTimer($this->userId);
    }
}
