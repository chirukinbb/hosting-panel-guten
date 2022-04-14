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
                'amount' => (object) [
                    'hand' => $player->getAmount(),
                    'bank' => $player->getBank()
                ]
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
        $this->table->title = $this->pokerTable->getTitle();
        $this->table->round =  (object) [
            'number'=>$this->pokerTable->getRoundNumber(),
            'ante'=>$this->pokerTable->getCurrentAnte()
        ];

        $this->pokerTable->eachPlayer(function (Player $player) {
            $this->table->players[$player->getPlace()]->round->isDealer = $player->isDealer();
            $this->table->players[$player->getPlace()]->round->isLB = $player->isLB();
            $this->table->players[$player->getPlace()]->round->isBB = $player->isBB();

            $this->table->players[$player->getPlace()]->amount = (object) [
                'hand' => $player->getAmount(),
                'bank' => $player->getBank()
            ];
        });

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
                    $this->table->players[$player->getPlace()]->timer = $this->pokerTable->getTimeOnTurn();
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
                    $this->table->players[$player->getPlace()]->hand->combo = $player->getCombo(0)->getName();
                });
            }
        });

        return $this;
    }

    public function flop()
    {
        $order = 0;

        $this->pokerTable->eachCardOnTable(function (Card $card) use (&$order){
            if ($order < 3) {
                $this->table->cards[] = (object)[
                    'nominal' => $card->getNominalIndex(),
                    'suit' => $card->getSuitIndex()
                ];
            }

            $order ++;
        });

        return $this;
    }

    public function turn()
    {
        $order = 0;

        $this->pokerTable->eachCardOnTable(function (Card $card) use (&$order){
            if ($order === 3) {
                $this->table->cards[] = (object)[
                    'nominal' => $card->getNominalIndex(),
                    'suit' => $card->getSuitIndex()
                ];
            }

            $order ++;
        });

        return $this;
    }

    public function river()
    {
        $order = 0;

        $this->pokerTable->eachCardOnTable(function (Card $card) use (&$order){
            if ($order === 4) {
                $this->table->cards[] = (object)[
                    'nominal' => $card->getNominalIndex(),
                    'suit' => $card->getSuitIndex()
                ];
            }

            $order ++;
        });

        return $this;
    }

    public function showdown()
    {
        $this->pokerTable->eachPlayer(function (Player $player) {
            $player->eachCard(function (Card $card) use ($player) {
                $this->table->players[$player->getPlace()]->hand->cards[] = (object)[
                    'nominal' => $card->getNominalIndex(),
                    'suit' => $card->getSuitIndex()
                ];
                $this->table->players[$player->getPlace()]->hand->combo = $player->getCombo();
            });
        });

        return $this;
    }

    /**
     * расчет банка согласно силе рук игроков
     */
    public function calculateBids()
    {}

    public function getTable(): object
    {
        return $this->table;
    }

    public function getChannelName($slug, $userId)
    {
        return $this->pokerTable->getChannelName($slug.'.'.$userId);
    }
}
