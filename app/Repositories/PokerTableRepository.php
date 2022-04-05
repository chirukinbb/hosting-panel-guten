<?php

namespace App\Repositories;

use App\Abstracts\AbstractPokerTable;
use App\Game\Player;
use App\Models\Game\Table;

class PokerTableRepository
{
    protected AbstractPokerTable $tableObj;
    protected object $table;

    public function __construct(protected int $id)
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

    public function setPlayers()
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

    public function startRound()
    {
        $number = $this->tableObj->getRoundNumber();
        $dealerPlace = $this->tableObj->getDealerPlace();
        $this->tableObj->startRound($number + 1);
        $this->tableObj->changeStatuses($dealerPlace + 1);
        $this->tableObj->payBlinds();
        $this->tableObj->preFlop();

        $this->tableObj->eachPlayer();

        $this->table->round =  (object) ['number'=>$number + 1];

        return $this;
    }

    public function playerTurn()
    {
        return $this;
    }

    public function actionFromPlayer(int $place, array $action)
    {
        return $this;
    }

    public function entTimeForTurn()
    {
        return $this;
    }

    public function flop()
    {
        return $this;
    }

    public function turn()
    {
        return $this;
    }

    public function river()
    {
        return $this;
    }

    public function showdown()
    {
        return $this;
    }

    public function save()
    {
        $table = Table::find($this->id);
        $table->object = $this->tableObj;
        $table->save();

        return $this;
    }

    /**
     * @return object
     */
    public function getTable(): object
    {
        return $this->table;
    }

    public function isTurnTransfer(): bool
    {
        return true;
    }

    public function isNewRound(): bool
    {
        return true;
    }

    public function isTableFinish(): bool
    {
        return true;
    }


}
