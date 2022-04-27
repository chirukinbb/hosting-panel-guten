<?php

namespace App\Events\Game\Broadcasters;

use App\Abstracts\AbstractBroadcaster;
use App\Repositories\PokerTableRepository;

class StartPokerRoundBroadcaster extends AbstractBroadcaster
{
    public function action(): \App\Builders\PokerTableBuilder
    {
        return $this->builder->setTable()
            ->setPlayers()
            ->startRound();
    }
}
