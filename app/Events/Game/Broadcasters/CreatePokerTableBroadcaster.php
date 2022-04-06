<?php

namespace App\Events\Game\Broadcasters;

use App\Abstracts\AbstractBroadcaster;
use App\Repositories\PokerTableRepository;

class CreatePokerTableBroadcaster extends AbstractBroadcaster
{
    protected string $event   = 'turn';
    protected string $broadcasterClassName = StartPokerRoundBroadcaster::class;

    public function actions():PokerTableRepository
    {
        return $this->repository->setTable()
            ->setPlayers();
    }

    public function broadcastWith()
    {
        return array_merge(
            parent::broadcastWith(),
            ['newChannel'=>$this->repository->getChannelName('table',$this->userId)]
        );
    }
}
