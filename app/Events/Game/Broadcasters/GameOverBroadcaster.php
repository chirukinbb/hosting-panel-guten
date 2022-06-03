<?php

namespace App\Events\Game\Broadcasters;

use App\Abstracts\AbstractBroadcaster;
use App\Builders\PokerTableBuilder;

class GameOverBroadcaster  extends AbstractBroadcaster
{
    public function action(): PokerTableBuilder
    {
        return $this->builder->losers();
    }
}
