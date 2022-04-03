<?php

namespace App\Events\Game\Broadcasters;

use App\Abstracts\AbstractBroadcaster;

class PlayersUpdateInPokerTableBroadcaster extends AbstractBroadcaster
{
    protected string $event = 'turn';

    public function __construct(
        public int $count,
        public string $screen,
        protected string $channel
    ) {}
}
