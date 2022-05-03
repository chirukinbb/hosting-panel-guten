<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\RiverBroadcaster;
use App\Repositories\PokerTableRepository;

class RiverPokerJob extends AbstractGameJob
{
    protected string $broadcasterClass = RiverBroadcaster::class;

    public function handle()
    {
        $this->setNextJobClass();

        parent::handle();
    }

    public function action(): PokerTableRepository
    {
        return $this->repository->setCurrentStepInRound(3);
    }

    public function setNextJobClass(): void
    {
        if ($this->repository->isShowDown() && $this->repository->isPrevTimeShowdown())
            $this->nextJobClass = SeparateBankJob::class;
        else
            $this->nextJobClass = StartAuctionForPlayerJob::class;
    }
}
