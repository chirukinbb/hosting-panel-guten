<?php

namespace App\Repositories;

use App\Abstracts\AbstractPokerTable;
use App\Game\Player;
use App\Models\Game\Table;

class PokerTableRepository
{
    protected AbstractPokerTable $tableObj;
    protected object $table;

    public function __construct(int $id)
    {
        $this->tableObj = Table::find($id)->object;
    }

    public function setTable()
    {
        $this->table = (object) [
            'title' => $this->tableObj->getTitle(),
            'blind' => $this->tableObj->getBlind(),
            'cardsInHand' => $this->tableObj->getCardsInHand(),
            'players' => []
        ];

        return $this;
    }

    protected function setPlayers()
    {
        $this->tableObj->eachPlayer(function (Player $player) {
            $this->table['players'][] = (object) [
                'name' => $player->getName(),
                'avatar' => $player->getAvatar(),
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
                    'hand' => $player->getAmount(),
                    'bank' => $player->getBank()
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

        return $this;
    }

    public function startRound(int $number)
    {
        $this->tableObj->startRound($number);
        // todo: remake
        $this->tableObj->changeStatuses($number / $this->tableObj->getCurrentPlayersCount());
        $this->tableObj->payBlinds();
        $this->tableObj->preFlop();

        return $this;
    }
}
