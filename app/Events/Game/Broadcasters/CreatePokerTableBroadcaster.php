<?php

namespace App\Events\Game\Broadcasters;

use App\Abstracts\AbstractBroadcaster;
use App\Repositories\PokerTableRepository;

class CreatePokerTableBroadcaster extends AbstractBroadcaster
{
    protected string $event   = 'turn';

    public function action(): \App\Builders\PokerTableBuilder
    {
        return $this->builder->setTable()
            ->setPlayers();
    }

    public function broadcastWith()
    {
        return array_merge(
            parent::broadcastWith(),
            ['newChannel'=>$this->builder->getChannelName('table',$this->userId)]
        );
    }
}
