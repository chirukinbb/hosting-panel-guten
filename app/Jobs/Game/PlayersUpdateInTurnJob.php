<?php

namespace App\Jobs\Game;

use App\Events\Game\Broadcasters\PlayersUpdateInPokerTableBroadcaster;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PlayersUpdateInTurnJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected string $className)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $players = \App\Models\Game\Player::whereTableClass($this->className)
            ->whereNotNull('searched')
            ->get('user_id');

        foreach ($players as $player) {
            broadcast(new PlayersUpdateInPokerTableBroadcaster(
                count($players),
                'loader',
                'turn.' . $player->user_id
            ));
        }
    }
}
