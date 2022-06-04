<?php

namespace App\Builders;

use App\Abstracts\AbstractGameAction;
use App\Abstracts\AbstractPokerTable;
use App\Game\Card;
use App\Game\Player;
use App\Repositories\PokerTableRepository;
use Illuminate\Notifications\Action;
use Illuminate\Support\Facades\Log;

class PokerTableBuilder
{
    protected AbstractPokerTable $pokerTable;
    protected object $table;

    public function __construct(protected int $tableId,protected int $userId)
    {
        $repository = new PokerTableRepository($this->tableId);
        $this->pokerTable = $repository->getTableObject();
        $this->setTable();
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
            'cardsInHand' => $this->pokerTable->getCardsInHand()
        ];

        $this->setPlayers();
        $this->setRound();

        return $this;
    }

    /**
     * билд игроков за столом
     */
    public function setPlayers()
    {
        $this->pokerTable->eachPlayer(function (Player $player) {
            $this->table->players[$player->getPlace()] = (object) [
                'id'  => $player->getUserId(),
                'name' => $player->getName(),
                'avatar' => $player->getAvatar(),
                'amount' => (object) [
                    'hand' => $player->getAmount(),
                    'bid' => $player->getBid()
                ],
                'action_result',
                'timer',
                'place',
                'rating'
            ];

            $this->setActions($player);
            $this->setPlayerRound($player);
        });
    }

    protected function setPlayerRound(Player  $player)
    {
        $this->table->players[$player->getPlace()]->round = (object)[
            'isActive'=>$player->isInRound(),
            'chips'=>(object)[
                'isDealer'=>$player->isDealer(),
                'isLB'=>$player->isLB(),
                'isBB'=>$player->isBB()
            ],
            'timer'=>$this->pokerTable->getTimerForPlayer($player),
            'combo'=>$player->getCombo($this->pokerTable->getCurrentStepInRound()),
            'showdown'=>(object)[
                'turn'=>$player->isCurrentShowdown()
            ]
        ];

        $this->setCardsForPlayer($player);
        $this->setCardsInCombo($player);
    }

    protected function setCardsInCombo(Player $player)
    {
        $player->getCombo($this->pokerTable->getCurrentStepInRound())->eachCard(function (Card $card) use ($player) {
            $this->table->players[$player->getPlace()]->round->showdown->cards[] = (object) [
                'nominal'=>$card->getNominalName(),
                'suit'=>$card->getSuitIndex()
            ];
        });
    }

    protected function setCardsForPlayer(Player $player)
    {
        $player->eachCard(function (Card $card) use ($player){
            $this->table->players[$player->getPlace()]->cards[] = (object)[
                'nominal'=>$card->getNominalName(),
                'suit'=>$card->getSuitIndex()
            ];
        });
    }

    protected function setActions(Player $player) {
        $player->eachAction(function (AbstractGameAction $action) use ($player){
            $this->table->players[$player->getPlace()]->actions->{'can'.$action->getName()} = $action->isActive();
        });
    }

    protected function setCards(Player $player) {
        $player->eachCard(function (Card $card) use ($player){
            $this->table->players[$player->getPlace()]->round->cards[] = (object)[
                'nominal'=>$card->getNominalName(),
                'suit'=>$card->getSuitIndex()
            ];
        });
    }

    /**
     * билд раунда для клиентов игры
     */
    public function setRound()
    {
        $this->table->title = $this->pokerTable->getTitle();
        $this->table->round =  (object) [
            'number'=>$this->pokerTable->getRoundNumber(),
            'step' => $this->pokerTable->getCurrentStepInRound(),
            'ante'=>$this->pokerTable->getCurrentAnte(),
            'bank'=> $this->pokerTable->getBank()
        ];

        switch ($this->pokerTable->getCurrentStepInRound()) {
            case 2:
                $this->flop();
                break;
            case 3:
                $this->turn();
                break;
            case 4:
            case 5:
            case 6:
                $this->river();
                break;
            default:
                $this->table->round->cards = [];
        }
    }

    /**
     * сдача первых 3-ех карт
     */
    protected function flop()
    {
        $order = 0;

        $this->pokerTable->eachCardOnTable(function (Card $card) use (&$order){
            if ($order < 3) {
                $this->table->round->cards[] = (object)[
                    'nominal' => $card->getNominalName(),
                    'suit' => $card->getSuitIndex()
                ];
            }

            $order ++;
        });
    }

    /**
     * сдача 4-ёх карты
     */
    protected function turn()
    {
        $order = 0;

        $this->pokerTable->eachCardOnTable(function (Card $card) use (&$order){
            if ($order < 4) {
                $this->table->round->cards[] = (object)[
                    'nominal' => $card->getNominalName(),
                    'suit' => $card->getSuitIndex()
                ];
            }

            $order ++;
        });
    }

    /**
     * сдача 5-и карты
     */
    protected function river()
    {
        $this->pokerTable->eachCardOnTable(function (Card $card) use (&$order){
            $this->table->round->cards[] = (object)[
                'nominal' => $card->getNominalName(),
                'suit' => $card->getSuitIndex()
            ];
        });
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
                'choose'=>!$player->isWinner()
            ];
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

                    $this->table->players[$player->getPlace()]->hand->combo = $player->getCombo(
                        $this->pokerTable->getCurrentStepInRound()
                    );
                });
            }
        });

        return $this;
    }

    public function showdownAction(): static
    {
        $this->pokerTable->eachPlayer(function (Player $player) {
            if ($player->isOpenCards()) {
                $player->eachCard(function (Card $card) use ($player){
                    $this->table->players[$player->getPlace()]->round->cards[] = (object)[
                        'nominal' => $card->getNominalIndex(),
                        'suit' => $card->getSuitIndex(),
                    ];

                    $this->table->players[$player->getPlace()]->hand->combo = $player->getCombo(3);
                });
            } else{
                unset($this->table->players[$player->getPlace()]->round->cards);
            }
        });

        return $this;
    }

    public function updateBidsBanks(): static
    {
        $this->updateBankInfo();
        $this->updateBidInfo();

        return $this;
    }

    /**
     * оповещение о действии игрока по результатам торга
     *
     * @return $this
     */
    public function addAlertAboutLastAction(): static
    {
        $this->pokerTable->eachPlayer(function (Player $player) {
            $player->eachAction(function (AbstractGameAction $action) use ($player){
                if ($player->getLastActionId() === $action->getId())
                    $this->table->players[$player->getPlace()]->message = $action->message(
                        $this->pokerTable->getCurrentBid()
                    );
            });
        });

        return $this;
    }

    public function losers(): static
    {
        $this->updateBidInfo();
        $this->updateBankInfo();

        $this->pokerTable->eachPlayer(function (Player $player) {
            if ($player->getAmount() === 0) {
                if ($this->userId) {
                    $this->table->players[$player->getPlace()]->exit = (object)[
                        'message' => sprintf('Your place: %s', $this->pokerTable->getPlaceInTable()),
                        'rating' => $this->pokerTable->getRatingByTable()
                    ];
                } else {
                    $this->table->players[$player->getPlace()]->message = sprintf(
                        'Finish with place: %s',
                        $this->pokerTable->getPlaceInTable()
                    );
                }
            }

        });

        return $this;
    }
}
