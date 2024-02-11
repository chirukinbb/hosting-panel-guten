<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\RiverBroadcaster;

class ShowdownPokerJob extends AbstractGameJob
{
    public function action()
    {
        $this->nextJobClass = $this->repository->isNewRound() ? StartPokerRoundJob::class : FinishTableJob::class;
        $this->repository->showdown();
    }
}
