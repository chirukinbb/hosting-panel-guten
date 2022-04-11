<?php

namespace App\Builders;

use App\Repositories\PokerTableRepository;

class PokerTableBuilder
{
    protected PokerTableRepository $repository;

    public function __construct(protected int $tableId,protected int $userId)
    {
        $this->repository = new PokerTableRepository($this->tableId);
    }

    public function startTimer()
    {
//        $this->table->players[$player->getPlace()]->actions[$action->getId()] = (object) [
//            'name'=>$action->getName(),
//            'is_active'=>$action->isActive()
//        ];
    }
}
