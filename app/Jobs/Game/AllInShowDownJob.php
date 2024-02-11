<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\AllInShowDownBroadcaster;
use App\Repositories\PokerTableRepository;

class AllInShowDownJob extends AbstractGameJob
{
    public function handle()
    {
        $this->setNextJobClass();

        parent::handle();
    }

    public function action(): PokerTableRepository
    {
        return $this->repository->showdown();
    }

    public function setNextJobClass()
    {
        if ($this->repository->isShowdownAction())
            $this->nextJobClass =  ActionShowDownJob::class;
        else
            $this->nextJobClass = SeparateBankJob::class;
    }
}
