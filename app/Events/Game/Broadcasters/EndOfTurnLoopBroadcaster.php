<?php

namespace App\Events\Game\Broadcasters;

use App\Abstracts\AbstractBroadcaster;

class EndOfTurnLoopBroadcaster extends AbstractBroadcaster
{
    public function action(): \App\Builders\PokerTableBuilder
    {
        return $this->builder->setTable()
            ->setPlayers()
            ->startRound()
            ->preFlop()
            ->flop()
            ->turn()
            ->endOfLoop();
    }
}
