<?php

namespace App\Events\Game\Broadcasters;

use App\Abstracts\AbstractBroadcaster;
use App\Jobs\Game\FinishPlayerAuctionJob;
use App\Repositories\PokerTableRepository;

class AuctionPokerRoundBroadcaster extends AbstractBroadcaster
{
    protected string $broadcasterClassName = '';

    public function action(): \App\Builders\PokerTableBuilder
    {
        return $this->builder->setTable()
            ->setPlayers()
            ->startRound()
            ->startTimer();
    }
}
