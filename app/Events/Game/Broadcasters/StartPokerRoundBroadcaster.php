<?php

namespace App\Events\Game\Broadcasters;

use App\Abstracts\AbstractBroadcaster;
use App\Repositories\PokerTableRepository;

class StartPokerRoundBroadcaster extends AbstractBroadcaster
{
    protected string $broadcasterClassName = AuctionPokerRoundBroadcaster::class;

    public function action(): \App\Builders\PokerTableBuilder
    {
        return $this->builder->startRound()
            ->preFlop($this->userId);
    }
}
