<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;

class StartPokerRoundJob extends AbstractGameJob
{
    protected string $nextJobClass = PreFlopPokerJob::class;

    public function action()
    {
        $this->repository->createRound();
    }
}
