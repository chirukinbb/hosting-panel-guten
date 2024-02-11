<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Repositories\PokerTableRepository;

class StartPlayerRoundOfBettingJob extends AbstractGameJob
{
    public function action(): PokerTableRepository
    {
        return $this->repository->startTimer();
    }

    public function handle()
    {
        FinishPlayerRoundOfBettingJob::dispatch($this->repository->getTableId())
            ->delay(now()->addSeconds($this->repository->getTimeOnTurn()));

        parent::handle();
    }
}
