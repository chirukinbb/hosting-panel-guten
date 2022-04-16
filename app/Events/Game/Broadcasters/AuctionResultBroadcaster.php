<?php

namespace App\Events\Game\Broadcasters;

use App\Abstracts\AbstractBroadcaster;

class AuctionResultBroadcaster extends AbstractBroadcaster
{
    protected string $broadcasterClassName = '';

    public function action(): \App\Builders\PokerTableBuilder
    {
        return $this->builder->setTable()
            ->startRound()
            ->preFlop($this->userId);
    }
}
