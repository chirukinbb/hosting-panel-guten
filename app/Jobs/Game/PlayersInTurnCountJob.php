<?php

namespace App\Jobs\Game;

use App\Events\Game\Broadcasters\PlayersUpdateInPokerTableBroadcaster;
use App\Events\PokerTableSearchingEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PlayersInTurnCountJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected string $className)
    {
    }

    public function handle()
    {
        $players = \App\Models\Game\Player::whereTableClass($this->className)
            ->whereNotNull('searched')
            ->get('user_id');

        foreach ($players as $player) {
            broadcast(new PokerTableSearchingEvent($this->className,$players->count(),$player->user_id));
        }
    }
}
