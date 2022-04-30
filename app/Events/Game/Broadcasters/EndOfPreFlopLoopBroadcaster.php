<?php

namespace App\Events\Game\Broadcasters;

use App\Abstracts\AbstractBroadcaster;

class EndOfPreFlopLoopBroadcaster extends AbstractBroadcaster
{
    public function action(): \App\Builders\PokerTableBuilder
    {
        return $this->builder->setTable()
            ->setPlayers()
            ->startRound()
            ->preFlop()
            ->endOfLoop();
    }
}
