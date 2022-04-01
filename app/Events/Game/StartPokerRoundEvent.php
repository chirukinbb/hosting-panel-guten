<?php

namespace App\Events\Game;

use App\Abstracts\AbstractBroadcaster;
use App\Models\Game\Table;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StartPokerRoundEvent extends AbstractBroadcaster
{
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        int $tableId,
        public string      $screen,
        protected string   $channel
    ) {
        $table = Table::find($tableId)->object;

        $table->startRound(1);
        $table->changeStatuses(0);
        $table->payBlinds();
        $table->preFlop();
    }
}
