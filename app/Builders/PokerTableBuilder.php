<?php

namespace App\Builders;

use App\Abstracts\AbstractGameAction;
use App\Abstracts\AbstractPokerTable;
use App\Game\Player;
use App\Jobs\Game\FinishPlayerTurnJob;
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

    public function getTable(): object
    {
        return $this->table;
    }
}
