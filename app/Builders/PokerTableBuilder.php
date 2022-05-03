<?php

namespace App\Builders;

use App\Abstracts\AbstractGameAction;
use App\Abstracts\AbstractPokerTable;
use App\Game\Card;
use App\Game\Player;
use App\Repositories\PokerTableRepository;
use Illuminate\Support\Facades\Log;

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
            $this->table->players[$player->getPlace()] = (object) [
                'name' => $player->getName(),
                'avatar' => $player->getAvatar(),
                'amount' => (object) [
                    'hand' => $player->getAmount(),
                    'bid' => $player->getBid()
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
            'ante'=>$this->pokerTable->getCurrentAnte(),
            'cards'=> []
        ];

        $this->pokerTable->eachPlayer(function (Player $player) {
            $this->table->players[$player->getPlace()]->round = (object) [
                'isDealer'=>$player->isDealer(),
                'isLB'=>$player->isLB(),
                'isBB'=>$player->isBB()
            ];
            $this->table->players[$player->getPlace()]->amount = (object) [
                'hand' => $player->getAmount(),
                'bid' => $player->getBid()
            ];
        });

        return $this;
    }

    /**
     * сдача карт в руку каждого игрока  отдельно
     * запись названия его текущей комбы
     *
     * @return $this
     */
    public function preFlop(): static
    {
        $this->pokerTable->eachPlayer(function (Player $player) {
            if ($player->getUserId() === $this->userId) {
                $player->eachCard(function (Card $card) use ($player) {
                    $this->table->players[$player->getPlace()]->round->cards[] = (object)[
                        'nominal' => $card->getNominalIndex(),
                        'suit' => $card->getSuitIndex(),
                    ];
                });

                $this->table->players[$player->getPlace()]->round->combo = $player->getCombo(0)->getName();
            }
        });

        return $this;
    }

    /**
     * стартуем отсчет таймера на ход игрока
     *
     * @return $this
     */
    public function startTimer(): static
    {
        $this->pokerTable->eachPlayer(function (Player $player) {
            if ($player->getUserId() === $this->userId){
                $player->eachAction(function (AbstractGameAction $action) use ($player) {
                    $this->table->players[$player->getPlace()]->actions[$action->getId()] = (object) [
                        'name'=>$action->getName(),
                        'is_active'=>$action->isActive()
                    ];
                });
            }

            $this->table->players[$player->getPlace()]->timer = ($this->pokerTable->getLastAuctionPlayerPlace() === $player->getPlace()) ?
                $this->pokerTable->getTimeOnTurn() : 0;
        });

        return $this;
    }

    /**
     * сдача первых 3-ех карт
     *
     * @return $this
     */
    public function flop()
    {
        $order = 0;

        $this->pokerTable->eachCardOnTable(function (Card $card) use (&$order){
            if ($order < 3) {
                $this->table->round->cards[] = (object)[
                    'nominal' => $card->getNominalIndex(),
                    'suit' => $card->getSuitIndex()
                ];
            }

            $order ++;
        });

        $this->pokerTable->eachPlayer(function (Player $player) {
            if ($player->getUserId() === $this->userId) {
                $this->table->players[$player->getPlace()]->round->combo = $player->getCombo(1)->getName();
            }
        });

        return $this;
    }

    /**
     * сдача 4-ой карты
     *
     * @return $this
     */
    public function turn()
    {
        $order = 0;

        $this->pokerTable->eachCardOnTable(function (Card $card) use (&$order){
            if ($order === 3) {
                $this->table->round->cards[] = (object)[
                    'nominal' => $card->getNominalIndex(),
                    'suit' => $card->getSuitIndex()
                ];
            }

            $order ++;
        });

        $this->pokerTable->eachPlayer(function (Player $player) {
            if ($player->getUserId() === $this->userId) {
                $this->table->players[$player->getPlace()]->round->combo = $player->getCombo(2)->getName();
            }
        });

        return $this;
    }

    /**
     * сдача 5-ой карты
     *
     * @return $this
     */
    public function river()
    {
        $order = 0;

        $this->pokerTable->eachCardOnTable(function (Card $card) use (&$order){
            if ($order === 4) {
                $this->table->round->cards[] = (object)[
                    'nominal' => $card->getNominalIndex(),
                    'suit' => $card->getSuitIndex()
                ];
            }

            $order ++;
        });

        $this->pokerTable->eachPlayer(function (Player $player) {
            if ($player->getUserId() === $this->userId) {
                $this->table->players[$player->getPlace()]->round->combo = $player->getCombo(3)->getName();
            }
        });

        return $this;
    }

    /**
     * раскрытие карт игроков, пошедших в олл-ин
     *
     * @return $this
     */
    public function showdown(): static
    {
        $this->pokerTable->eachPlayer(function (Player $player) {
            if ($player->getLastActionId() === 3) {
                $player->eachCard(function (Card $card) use ($player) {
                    $this->table->players[$player->getPlace()]->hand->cards[] = (object)[
                        'nominal' => $card->getNominalIndex(),
                        'suit' => $card->getSuitIndex()
                    ];
                    $this->table->players[$player->getPlace()]->hand->combo = $player->getCombo(3);
                });
            }
        });

        return $this;
    }

    /**
     * обновление инфы о ставках игроков
     */
    protected function updateBidInfo(): static
    {
        $this->pokerTable->eachPlayer(function (Player $player) {
            $this->table->players[$player->getPlace()]->amount = (object) [
                'hand' => $player->getAmount(),
                'bid' => $player->getBid()
            ];
        });

        return $this;
    }

    protected function updateBankInfo(): static
    {
        $this->table->round->bank = (object) $this->pokerTable->getBank();

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

    public function preFlopAuctionResult(): static
    {
        return $this;
    }

    public function flopAuctionResult(): static
    {
        return $this;
    }

    public function turnAuctionResult(): static
    {
        return $this;
    }

    public function riverAuctionResult(): static
    {
        return $this;
    }

    public function endOfLoop(): static
    {
        return $this->updateBidInfo()
            ->updateBankInfo();
    }

    public function startShowdownAction(): static
    {
        $this->pokerTable->eachPlayer(function (Player $player){
            $this->table->showdown = (object) [
                'is_active'=>$player->isCurrentShowdown(),
                'hidden'=>!$player->isWinner()
            ];
        });

        return $this;
    }

    public function endTimeOnShowdownAction(): static
    {
        // todo: create it!
        return $this;
    }

    public function endTimeOnPreFlopAuction(): static
    {
        // todo: create it!
        return $this;
    }

    public function updateBidsBanks(): static
    {
        $this->updateBankInfo();
        $this->updateBidInfo();

        return $this;
    }

    public function addAlertAboutLastAction(): static
    {
        $this->pokerTable->eachPlayer(function (Player $player) {
            if ($player->getUserId() === $this->userId){
                $player->eachAction(function (AbstractGameAction $action) use ($player){
                    if ($player->getLastActionId() === $action->getId())
                        $this->table->players[$player->getPlace()]->message = $action->message(
                            $this->pokerTable->getCurrentBid()
                        );
                });
            }
        });

        return $this;
    }

    public function losers()
    {
        $this->updateBidInfo();
        $this->updateBankInfo();

        $this->pokerTable->eachPlayer(function (Player $player) {
            if ($this->userId && $player->getAmount() === 0)
                $this->table->players[$player->getPlace()]->exit = (object) [
                    'message'=>sprintf('Your place: %s', $this->pokerTable->getPlaceInTable()),
                    'rating'=>$this->pokerTable->getRatingByTable()
                ];
        });

        return $this;
    }

    public function getRatingByTable(): float|int
    {
        return $this->pokerTable->getRatingByTable();
    }
}
