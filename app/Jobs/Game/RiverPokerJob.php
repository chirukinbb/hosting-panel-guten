<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\RiverBroadcaster;

class RiverPokerJob extends AbstractGameJob
{
    public function action()
    {
        $this->nextJobClass = $this->repository->isNewLoop() ? StartPlayerRoundOfBettingJob::class : ShowdownPokerJob::class;
        $this->repository->startRiverStep();
    }
}
