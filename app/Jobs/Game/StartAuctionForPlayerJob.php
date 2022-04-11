<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\AuctionPokerRoundBroadcaster;
use App\Repositories\PokerTableRepository;

class StartAuctionForPlayerJob extends AbstractGameJob
{
    protected string $broadcasterClass = AuctionPokerRoundBroadcaster::class;

    public function action(): PokerTableRepository
    {
        return $this->repository->startTimer();
    }
}
