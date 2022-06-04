<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\PreFlopBroadcaster;
use App\Repositories\PokerTableRepository;

class PreFlopPokerJob extends AbstractGameJob
{
    protected string $nextJobClass = StartAuctionForPlayerJob::class;

    public function action(): PokerTableRepository
    {
        return $this->repository->setCurrentStepInRound(0);
    }
}
