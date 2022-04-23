<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\FlopBroadcaster;
use App\Events\Game\Broadcasters\TurnBroadcaster;
use App\Repositories\PokerTableRepository;

class TurnPokerJob extends AbstractGameJob
{
    protected string $broadcasterClass = TurnBroadcaster::class;
    protected string $nextJobClass = StartAuctionForPlayerJob::class;

    public function action(): PokerTableRepository
    {
        return $this->repository->setCurrentStepInRound(2);
    }
}
