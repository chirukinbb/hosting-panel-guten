<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\FlopBroadcaster;
use App\Events\Game\Broadcasters\TurnBroadcaster;

class TurnPokerJob extends AbstractGameJob
{
    public function action()
    {
        $this->nextJobClass = $this->repository->isNewLoop() ? StartPlayerRoundOfBettingJob::class : RiverPokerJob::class;
        $this->repository->startTurnStep();
    }
}
