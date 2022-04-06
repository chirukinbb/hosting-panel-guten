<?php

namespace App\Events\Game\Broadcasters;

use App\Abstracts\AbstractBroadcaster;
use App\Repositories\PokerTableRepository;

class StartPokerRoundBroadcaster extends AbstractBroadcaster
{
    protected string $broadcasterClassName = AuctionPokerRoundBroadcaster::class;

    public function actions():PokerTableRepository
    {
        return $this->repository->startRound()
            ->setPlayers();
    }
}
