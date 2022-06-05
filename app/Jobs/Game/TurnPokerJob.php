<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\FlopBroadcaster;
use App\Events\Game\Broadcasters\TurnBroadcaster;
use App\Repositories\PokerTableRepository;

class TurnPokerJob extends AbstractGameJob
{
    public function handle()
    {
        $this->setNextJobClass();

        parent::handle();
    }

    public function action(): PokerTableRepository
    {
        return $this->repository->startTurnStep();
    }

    public function setNextJobClass(): void
    {
        if ($this->repository->isShowDown() && $this->repository->isPrevTimeShowdown())
            $this->nextJobClass = RiverPokerJob::class;
        else
            $this->nextJobClass = StartAuctionForPlayerJob::class;
    }
}
