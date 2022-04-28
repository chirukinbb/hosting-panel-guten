<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\AllInShowDownBroadcaster;
use App\Repositories\PokerTableRepository;

class StartShowdownPokerJob extends AbstractGameJob
{
    protected string $broadcasterClass = AllInShowDownBroadcaster::class;
    protected string $nextJobClass = StartPokerRoundJob::class;

    public function action(): PokerTableRepository
    {
       return $this->repository->showdown();
    }
}
