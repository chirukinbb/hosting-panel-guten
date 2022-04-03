<?php

namespace App\Events\Game\Broadcasters;

use App\Abstracts\AbstractBroadcaster;

class CreatePokerTableBroadcaster extends AbstractBroadcaster
{
    public string $newEvent = 'table';
    protected string $event = 'turn';

    public function __construct(int $tableId, string $screen, string $channel)
    {
        parent::__construct($tableId, $screen, $channel);

        $this->table = $this->repository->setTable()
            ->setPlayers()
            ->save()
            ->getTable();

        self::broadcast(new StartPokerRoundBroadcaster($tableId,$screen,$channel));
    }
}
