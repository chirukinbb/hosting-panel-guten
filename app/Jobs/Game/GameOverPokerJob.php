<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\GameOverBroadcaster;
use App\Repositories\PokerTableRepository;

class GameOverPokerJob extends AbstractGameJob
{
    public function handle()
    {
        parent::handle();
    }

    public function action(): PokerTableRepository
    {
        return $this->repository->losers();
    }

    public function setNextJobClass(): void
    {
        if ($this->repository->isTableFinish())
            $this->nextJobClass = FinishTableJob::class;
        else
            $this->nextJobClass = StartPokerRoundJob::class;
    }
}
