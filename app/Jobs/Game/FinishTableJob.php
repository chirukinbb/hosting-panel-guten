<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\GameOverBroadcaster;
use App\Repositories\PokerTableRepository;

class FinishTableJob extends AbstractGameJob
{
    protected string $broadcasterClass = GameOverBroadcaster::class;

    public function action(): PokerTableRepository
    {
        return $this->repository->finishTable();
    }
}
