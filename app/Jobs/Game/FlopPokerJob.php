<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;

class FlopPokerJob extends AbstractGameJob
{

    public function action()
    {
        $this->nextJobClass = $this->repository->isNewLoop() ? StartPlayerRoundOfBettingJob::class : TurnPokerJob::class;
        $this->repository->startFlopStep();
    }
}
