<?php

namespace App\Repositories;

use App\Abstracts\AbstractGameAction;
use App\Abstracts\AbstractPokerTable;
use App\Game\Player;
use App\Models\Game\Table;

class PokerTableRepository
{
    protected AbstractPokerTable $tableObj;
    protected object $table;

    public function __construct(protected int $tableId)
    {
        $tableObj = Table::find($this->tableId);

        $this->tableObj = $tableObj->object;
        //..$this->deletedJobId = $tableObj->removed_job_id ?? 0;
    }

    public function __destruct()
    {
        Table::updateOrCreate(
            ['id' => $this->tableId],
            ['object' => $this->tableObj]
        );
    }

    public function startTurnStep(): static
    {
        $this->tableObj->setCurrentStepInRound($this->tableObj::ROUND_TURN_STEP);

        return $this;
    }

    public function startPreFlopStep(): static
    {
        $this->tableObj->setCurrentStepInRound($this->tableObj::ROUND_PREFLOP_STEP);

        return $this;
    }

    public function startFlopStep(): static
    {
        $this->tableObj->setCurrentStepInRound($this->tableObj::ROUND_FLOP_STEP);

        return $this;
    }

    public function startRiverStep(): static
    {
        $this->tableObj->setCurrentStepInRound($this->tableObj::ROUND_RIVER_STEP);

        return $this;
    }

    public function getCurrentStepInRound()
    {
        return $this->tableObj->getCurrentStepInRound();
    }

    /**
     * @return int
     */
    public function getTableId(): int
    {
        return $this->tableId;
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

    static public function instance(string $tableClass): PokerTableRepository
    {
        $table = Table::getModel();

        $table->object = new $tableClass(now()->timestamp);
        $table->table_class = $tableClass;
        $table->status = Table::CONTINUE;

        $table->save();

        return new self($table->id);
    }

    /**
     * заполнение стола игроками
     *
     * @return $this
     */
    public function createTable(): static
    {
        $players = \App\Models\Game\Player::whereTableClass(get_class($this->tableObj))
            ->whereNotNull('searched')
            ->limit($this->tableObj->getPlayersCount())
            ->get();

        foreach ($players as $player) {
            $this->tableObj->setPlayer(
                $player->user_id,
                $player->user->data?->public_name ?? $player->user->name,
                asset($player->user->data?->avatar_path ?? 'img/JohnDoe.webp')
            );

            $player->searched = null;
            $player->gamed = $this->tableId;
            $player->save();
        }

        return $this;
    }

    /**
     * создание нового раунда
     *
     * @return $this
     */
    public function createRound(): static
    {
        $this->tableObj->removePlayersCards();
        $this->tableObj->removePlayersCombos();

        $this->tableObj->startRound();
        $this->tableObj->changeStatuses();
        $this->tableObj->payBlinds();
        $this->tableObj->preFlop();
        $this->tableObj->calculateHandValues();
        $this->tableObj->flop();
        $this->tableObj->calculateHandValues();
        $this->tableObj->turn();
        $this->tableObj->calculateHandValues();
        $this->tableObj->river();
        $this->tableObj->calculateHandValues();
        $this->tableObj->setCurrentStepInRound($this->tableObj::ROUND_START_STEP);

        return $this;
    }

    public function getTimeOnTurn()
    {
        return $this->tableObj->getTimeOnTurn();
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

    /**
     * у игрока закончилось время на ход
     *
     * @return $this
     */
    public function entTimeForAction(): static
    {
        $this->tableObj->eachPlayer(function (Player $player) {
            if ($this->tableObj->getLastAuctionPlayerPlace() === $player->getPlace()) {
                $player->eachAction(function (AbstractGameAction $action) {
                    $action->setIsActive(false);
                });

                if ($this->tableObj->getCurrentBid() > $player->getBid())
                    $this->fold();
                else
                    $this->check();
            }
        });

        return $this;
    }

    /**
     * конец этапа в раунде:
     * перевод ставок в общий банк
     *
     * @return $this
     */
    public function lapFinish(): static
    {
        $this->tableObj->bidsToBank();

        return $this;
    }

    /**
     * конец этапа в раунде:
     * перевод ставок в общий банк
     * выплата победителям
     *
     * @return $this
     */
    public function roundFinish(): static
    {
        $this->tableObj->bidsToBank();
        $this->tableObj->payToWinners();

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
        return $this->tableObj->isTurnTransfer();
    }

    public function isNewRoundWithoutShowdown(): bool
    {
        return $this->tableObj->isNewRoundWithoutShowdown();
    }

    public function isTableFinish(): bool
    {
        return $this->tableObj->isTableFinish();
    }

    public function isNewLoop(): bool
    {
        return $this->tableObj->isNewLoop();
    }

    public function isShowDown(): bool
    {
        return $this->tableObj->isShowDown();
    }

    /**
     * Turn actions
     */
    public function fold()
    {
        $this->tableObj->fold();

        return $this;
    }

    public function check()
    {
        $this->tableObj->check();

        return $this;
    }

    public function raise(int $amount)
    {
        $this->tableObj->raise($amount);

        return $this;
    }

    public function default()
    {
        $this->tableObj->default();

        return $this;
    }

    public function call()
    {
        $this->tableObj->call();

        return $this;
    }

    public function getTableObject()
    {
        return $this->tableObj;
    }

    public function showdown(): static
    {
        $this->tableObj->setCurrentStepInRound($this->tableObj::ROUND_SHOWDOWN_STEP);
        $this->tableObj->payToWinners();
        $this->tableObj->eachPlayer(function (Player $player) {
            if ($player->getLastActionId() === 3) {
                $player->setIsOpenCards(true);
                $player->setIsShowdownPass(true);
            }
        });

        return $this;
    }

    public function showdownPlayerActions(): static
    {
        $this->tableObj->showdownPlayerActions();

        return $this;
    }

    public function isNewRound()
    {
        $isCount = $this->tableObj->getActivePlayersCount() > 1;
        $isStep = $this->getCurrentStepInRound() === 3;

        return $isCount && $isStep;
    }

    public function isExtractBidsToBank(): bool
    {
        return $this->tableObj->isExtractBidsToBank();
    }

    public function bidsToBank(): static
    {
        $this->tableObj->bidsToBank();

        return $this;
    }

    public function resultOfTurnAction(): static
    {
        return $this;
    }

    public function isShowdownAction(): bool
    {
        return $this->tableObj->isShowdownAction();
    }

    public function payments(): static
    {
        $this->tableObj->setCurrentStepInRound($this->tableObj::ROUND_PAYMENT_STEP);
        $this->tableObj->payToWinners();

        return $this;
    }

    public function isPrevTimeShowdown(): bool
    {
        return $this->getCurrentStepInRound() !== 3;
    }

    function existsLosers(): bool
    {
        return $this->tableObj->existsLosers();
    }

    public function losers(): static
    {
        $this->tableObj->eachPlayer(function (Player $player) {
            if ($player->getAmount() === 0) {
                $player->setInRound(false);
                $this->tableObj->addLostPlayer($player);
            }
        });

        $this->tableObj->setActivePlayersCountOnEndRound();

        $this->tableObj->eachLooser(function (Player $player) {
            $player->setPlaceInGame($this->tableObj->avgPlace());
            \App\Models\Game\Player::whereUserId($player->getUserId())->where('table_class', get_class($this->tableObj))
                ->increment('rating', $this->tableObj->gerRatingByPlace($this->tableObj->avgPlace()));
        });

        return $this;
    }

    public function isNextPlayerShowdownAction()
    {
        return $this->tableObj->isNextPlayerShowdownAction();
    }

    public function setShowdownAction(bool $param)
    {
        $this->tableObj->setShowdownAction($param);
    }

    public function result(Player $player)
    {
        return $this->tableObj->result($player);
    }

    public function getRoundNumber()
    {
        return $this->tableObj->getRoundNumber();
    }

    public function getActivePlayerId()
    {
        return $this->tableObj->getActivePlayerId();
    }

    public function finishPlayerRoundOfBetting()
    {
        $this->tableObj->finishPlayerRoundOfBetting();
    }

    public function isNewLoopWithoutBid()
    {
        return $this->tableObj->isNewLoopWithoutBid();
    }
}
