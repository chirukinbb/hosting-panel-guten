<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\ShowDownBroadcaster;
use App\Repositories\PokerTableRepository;

class StartShowdownPokerJob extends AbstractGameJob
{
    protected string $broadcasterClass = ShowDownBroadcaster::class;
    protected string $nextJobClass = StartPokerRoundJob::class;

    public function action(): PokerTableRepository
    {
       return $this->repository->showdown();
    }
}
