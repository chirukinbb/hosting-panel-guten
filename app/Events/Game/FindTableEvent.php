<?php

namespace App\Events\Game;

use App\Abstracts\AbstractPokerTable;
use App\Game\Player;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FindTableEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $users = [];

    public function __construct(
        AbstractPokerTable $table,
        public string $screen,
        protected string $channel
    ) {
        $this->setPlayers($table);
    }

    public function broadcastOn()
    {
        return new Channel($this->channel);
    }

    public function broadcastAs()
    {
        return 'table';
    }

    protected function setPlayers(AbstractPokerTable $table)
    {
        $table->eachPlayer(function (Player $player) {
            $user = User::find($player);

            $this->users[] = (object) [
                'place'=>$player->getPlace(),
                'avatar'=>$user->data->avatar_path,
                'name'=>$user->data->public_name
            ];
        });
    }
}
