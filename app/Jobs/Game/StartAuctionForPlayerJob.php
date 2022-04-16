<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\AuctionPokerRoundBroadcaster;
use App\Repositories\PokerTableRepository;

class StartAuctionForPlayerJob extends AbstractGameJob
{
    protected string $broadcasterClass = AuctionPokerRoundBroadcaster::class;

    public function action(): PokerTableRepository
    {
        dispatch(new FinishPlayerTurnJob($this->classNameOrTableId))
            ->delay(now()->addSeconds($this->repository->getTimeOnTurn()));// ставим задачу на прекращение хода

        return $this->repository->startTimer();
    }
}
