<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\FlopBroadcaster;
use App\Repositories\PokerTableRepository;

class FlopPokerJob extends AbstractGameJob
{
    protected string $broadcasterClass = FlopBroadcaster::class;

    public function handle()
    {
        $this->setNextJobClass();

        parent::handle();
    }

    public function action(): PokerTableRepository
    {
        return $this->repository->setCurrentStepInRound(1);
    }

    public function setNextJobClass(): void
    {
        if ($this->repository->isShowDown() && $this->repository->isPrevTimeShowdown())
            $this->nextJobClass = TurnPokerJob::class;
        else
            $this->nextJobClass = StartAuctionForPlayerJob::class;
    }
}
