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

    public array $table = [];
    public string $newEvent = 'table';

    public function __construct(
        AbstractPokerTable $table,
        public string      $screen,
        protected string   $channel
    ) {
        $this->table = [
            'title' => $table->getTitle(),
            'blind' => $table->getBlind(),
            'cardsInHand' => $table->getCardsInHand(),
            'players' => []
        ];

        $this->setPlayers($table);
    }

    public function broadcastOn()
    {
        return new Channel($this->channel);
    }

    public function broadcastAs()
    {
        return 'turn';
    }

    protected function setPlayers(AbstractPokerTable $table)
    {
        $table->eachPlayer(function (Player $player) {
            $user = User::find($player->getPlayerId());

            $this->table['players'][] = [
                'name' => $user->data?->public_name ?? $user->name,
                'avatar' => ($data = $user->data) ? asset($data->avatar_path) : null,
//                'myTurn' => false,
//                'isDealer' => false,
//                'isBB' => false,
//                'isLB' => false,
//                'actions' => [
//                    'canCall' => true,
//                    'canCheck' => true,
//                    'canBet' => true,
//                    'canRaise' => true,
//                    'canAllIn' => true
//                ],
//                'hand' => [
//                    'cards' => [
//                        ['nominal' => 5, 'suit' => 2],
//                    ],
//                    'combo' => 'jjjjj',
//                    'amount' => 1000,
//                    'inGame' => true
//                ],
                'amount' => [
                    'hand' => 1000,
                    'bank' => 0
                ],
//                'action' => [
//                    'message' => 'Raise to 100',
//                    'hand' => 452,
//                    'bank' => 100
//                ],
//                'timer' => [
//                    'start' => 0
//                ]
            ];
        });
    }
}
