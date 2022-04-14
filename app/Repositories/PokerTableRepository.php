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
        //dd($players)    ;

        foreach ($players as $player) {
            $this->tableObj->setPlayer(
                $player->user_id,
                $player->user->data?->public_name ?? $player->user->name,
                asset($player->user->data?->avatar_path ?? 'img/JohnDoe.webp')
            );

            $player->searched = null;
            $player->gamed = $this->tableId;
            //$player->save();
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

    public function save()
    {
        Table::updateOrCreate(
            ['id' => $this->tableId],
            ['object' => $this->tableObj]
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
