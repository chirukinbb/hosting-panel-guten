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

    public function __construct(protected int $tableId)
    {
        $this->tableObj = Table::find($this->tableId)?->object;
    }

    public function setCurrentStepInRound(int $step)
    {
        $this->tableObj->setCurrentStepInRound($step);

        return $this;
    }

    public function getCurrentStepInRound()
    {
        return $this->tableObj->getCurrentStepInRound();
    }

    public function getChannelName($slug, $userId)
    {
        return $this->tableObj->getChannelName($slug . '.' . $userId);
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
        $this->tableObj->startRound();
        $this->tableObj->changeStatuses();
        $this->tableObj->payBlinds();
        $this->tableObj->removePlayersCards();
        $this->tableObj->removePlayersCombos();
        $this->tableObj->preFlop();
        $this->tableObj->calculateHandValues();
        $this->tableObj->flop();
        $this->tableObj->calculateHandValues();
        $this->tableObj->turn();
        $this->tableObj->calculateHandValues();
        $this->tableObj->river();
        $this->tableObj->calculateHandValues();

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

    public function save($broadcasterClass = null)
    {
        $updateAttrs = ['object' => $this->tableObj];

        if ($broadcasterClass)
            $updateAttrs = array_merge($updateAttrs,['broadcaster_class'=>$broadcasterClass]);

        Table::updateOrCreate(
            ['id' => $this->tableId],
            $updateAttrs
        );
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

    public function isNewLoop():bool
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

    public function allIn()
    {
        $this->tableObj->allIn();

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
        $this->tableObj->payToWinners();

        return $this;
    }
}
