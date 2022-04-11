<?php

namespace App\Repositories;

use App\Abstracts\AbstractGameAction;
use App\Abstracts\AbstractPokerTable;
use App\Game\Card;
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

    public function getChannelName($slug, $userId)
    {
        return $this->tableObj->getChannelName($slug.'.'.$userId);
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
            $this->table->players[] = (object) [
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

    /**
     * действия при остаче одного игрока за столом
     *
     * @return $this
     */
    public function finishTable(): static
    {
        return $this;
    }

    public function createRound(): static
    {
        $number = $this->tableObj->getRoundNumber();
        $dealerPlace = $this->tableObj->getDealerPlace();
        $this->tableObj->startRound($number + 1);
        $this->tableObj->changeStatuses($dealerPlace + 1);
        $this->tableObj->payBlinds();
        $this->tableObj->removePlayersCards();
        $this->tableObj->preFlop();
        $this->tableObj->flop();
        $this->tableObj->turn();
        $this->tableObj->river();

        return $this;
    }

    public function getTimeOnTurn()
    {
        return $this->tableObj->getTimeOnTurn();
    }

    public function startRound(): static
    {
        $this->table->round =  (object) ['number'=>$this->tableObj->getRoundNumber()];

        return $this;
    }

    public function preFlop(int $userId): static
    {
        $this->tableObj->eachPlayer(function (Player $player) use ($userId){
            if ($player->getUserId() === $userId) {
                $player->eachCard(function (Card $card) use ($player) {
                    $this->table->players[$player->getPlace()]->hand->cards[] = (object)[
                        'nominal' => $card->getNominalIndex(),
                        'suit' => $card->getSuitIndex()
                    ];
                });
            }
        });

        return $this;
    }

    public function startTimer(): static
    {
        $this->tableObj->setNextPlayerAuction();

        return $this;
    }

    public function eachPlayer(callable $func)
    {
        $this->tableObj->eachPlayer($func);
    }

    public function actionFromPlayer(int $place, array $action): static
    {
        return $this;
    }

    /**
     * у игрока закончилось время на ход
     *
     * @return $this
     */
    public function entTimeForTurn(): static
    {
        $this->tableObj->eachPlayer(function (Player $player) {
            if ($this->tableObj->getAuctionUserId() === $player->getUserId()) {
                $player->eachAction(function (AbstractGameAction $action) {
                    $action->setIsActive(false);
                });
            }
        });

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
        return $this->tableObj;
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

    public function getTableObject()
    {
        return $this->tableObj;
    }
}
