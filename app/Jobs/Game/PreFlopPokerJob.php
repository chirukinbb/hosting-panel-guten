<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\PreFlopBroadcaster;

class PreFlopPokerJob extends AbstractGameJob
{
    protected string $nextJobClass = StartPlayerRoundOfBettingJob::class;

    public function action()
    {
        $this->repository->startPreFlopStep();
    }
}
