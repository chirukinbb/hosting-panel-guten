<?php

namespace App\Events\Game\Broadcasters;

use App\Abstracts\AbstractBroadcaster;
use App\Abstracts\AbstractPokerTable;
use App\Game\Player;
use App\Models\Game\Table;

class StartPokeRoundBroadcaster extends AbstractBroadcaster
{
    public array $table = [];
    public string $newEvent = 'table';
    protected string $event = 'turn';

    public function __construct(int $tableId, string $screen, string $channel)
    {
        parent::__construct($tableId, $screen, $channel);

        $this->table->object->startRound(1);
        $this->table->object->changeStatuses(0);
        $this->table->object->payBlinds();
        $this->table->object->preFlop();
    }
}
