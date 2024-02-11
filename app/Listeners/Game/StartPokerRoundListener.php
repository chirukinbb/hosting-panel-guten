<?php

namespace App\Listeners\Game;

use App\Events\Game\StartPokerRoundEvent;
use App\Models\Game\Table;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StartPokerRoundListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function handle(StartPokerRoundEvent $event)
    {
        $table = Table::find($event->tableId);

        $table->object->startRound(1);
        $table->object->changeStatuses(0);
        $table->object->payBlinds();
        $table->object->preFlop();

        broadcast(new StartPokerRoundEvent());
    }
}
