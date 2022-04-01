<?php

namespace App\Events\Game\Broadcasters;

use App\Abstracts\AbstractBroadcaster;
use App\Game\Player;
use App\Models\User;

class CreatePokerTableEvent extends AbstractBroadcaster
{
    public array $table = [];
    public string $newEvent = 'table';
    protected string $event = 'turn';

    public function __construct(int $tableId, string $screen, string $channel)
    {
        parent::__construct($tableId, $screen, $channel);

        $this->table = [
            'title' => $this->tableObj->getTitle(),
            'blind' => $this->tableObj->getBlind(),
            'cardsInHand' => $this->tableObj->getCardsInHand(),
            'players' => []
        ];

        $this->setPlayers();
    }

    protected function setPlayers()
    {
        $this->tableObj->eachPlayer(function (Player $player) {
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
