<?php

namespace App\Events\Game\Broadcasters;

use App\Abstracts\AbstractBroadcaster;

class CreatePokerTableBroadcaster extends AbstractBroadcaster
{
    public string $newChannel;
    protected string $event   = 'turn';

    public function __construct(int $tableId, string $screen, string $channel, int $userId)
    {
        parent::__construct($tableId, $screen, $channel);

        $this->table = $this->repository->setTable()
            ->setPlayers()
            ->save()
            ->getTable();
        $this->newChannel  = $this->repository->getChannelName('table',$userId);

        //self::broadcast(new StartPokerRoundBroadcaster($tableId,$screen,$channel));
    }
}
