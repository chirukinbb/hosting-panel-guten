<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\FlopBroadcaster;
use App\Repositories\PokerTableRepository;

class FlopPokerJob extends AbstractGameJob
{
    protected string $broadcasterClass = FlopBroadcaster::class;
    protected string $nextJobClass = StartAuctionForPlayerJob::class;

    public function action(): PokerTableRepository
    {
        return $this->repository->setCurrentStepInRound(1);
    }
}
