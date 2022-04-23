<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\RiverBroadcaster;
use App\Repositories\PokerTableRepository;

class RiverPokerJob extends AbstractGameJob
{
    protected string $broadcasterClass = RiverBroadcaster::class;
    protected string $nextJobClass = StartAuctionForPlayerJob::class;

    public function action(): PokerTableRepository
    {
        return $this->repository->setCurrentStepInRound(3);
    }
}
