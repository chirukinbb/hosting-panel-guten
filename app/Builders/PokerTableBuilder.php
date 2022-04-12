<?php

namespace App\Builders;

use App\Abstracts\AbstractGameAction;
use App\Abstracts\AbstractPokerTable;
use App\Game\Card;
use App\Game\Player;
use App\Repositories\PokerTableRepository;

class PokerTableBuilder
{
    protected AbstractPokerTable $pokerTable;
    protected object $table;

    public function __construct(protected int $tableId,protected int $userId)
    {
        $repository = new PokerTableRepository($this->tableId);
        $this->pokerTable = $repository->getTableObject();
    }

    /**
     * подготовка обьекта стола для клиентов игры
     *
     * @return $this
     */
    public function setTable()
    {
        $this->table = (object) [
            'title' => $this->pokerTable->getTitle(),
            'blind' => $this->pokerTable->getBlind(),
            'cardsInHand' => $this->pokerTable->getCardsInHand(),
            'players' => []
        ];

        return $this;
    }

    /**
     * розсадка игроков за столом
     *
     * @return $this
     */
    public function setPlayers()
    {
        $this->pokerTable->eachPlayer(function (Player $player) {
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
     * старт нового раунда в клиенте игры
     *
     * @return $this
     */
    public function startRound(): static
    {
        $this->table->round =  (object) ['number'=>$this->pokerTable->getRoundNumber()];

        return $this;
    }

    /**
     * стартуем отсчет таймера на ход игрока
     *
     * @param int $userId
     * @return $this
     */
    public function startTimer(int $userId): static
    {
        $this->pokerTable->eachPlayer(function (Player $player) use ($userId){
            if ($player->getUserId() === $userId){
                $player->eachAction(function (AbstractGameAction $action) use ($player) {
                    $this->table->players[$player->getPlace()]->actions[$action->getId()] = (object) [
                        'name'=>$action->getName(),
                        'is_active'=>$action->isActive()
                    ];
                });
            } else {
                $this->table->players[$player->getPlace()]->timer = ($this->pokerTable->getAuctionUserId() === $player->getUserId()) ?
                    $this->pokerTable->getTimeOnTurn() : 0;
            }
        });

        return $this;
    }

    public function preFlop(int $userId): static
    {
        $this->pokerTable->eachPlayer(function (Player $player) use ($userId){
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

    public function getTable(): object
    {
        return $this->table;
    }

    public function getChannelName($slug, $userId)
    {
        return $this->pokerTable->getChannelName($slug.'.'.$userId);
    }
}
