<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\CreatePokerTableBroadcaster;
use App\Repositories\PokerTableRepository;

class EndOfLoopJob extends AbstractGameJob
{
    protected string $broadcasterClass = CreatePokerTableBroadcaster::class;
    protected string $nextJobClass = StartPokerRoundJob::class;

    public function action(): PokerTableRepository
    {
        return $this->repository->createTable();
    }
}
