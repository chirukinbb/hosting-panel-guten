<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\StartPokerRoundBroadcaster;
use App\Repositories\PokerTableRepository;

class StartPokerRoundJob extends AbstractGameJob
{
    protected string $broadcasterClass = StartPokerRoundBroadcaster::class;
    protected string $nextJobClass = PreFlopPokerJob::class;

    public function action(): PokerTableRepository
    {
        return $this->repository->createRound();
    }
}
