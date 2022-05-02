<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\AllInShowDownBroadcaster;
use App\Repositories\PokerTableRepository;

class AllInShowDownJob extends AbstractGameJob
{
    protected string $broadcasterClass = AllInShowDownBroadcaster::class;

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
        $this->nextJobClass = $this->repository->isShowdownAction() ?
            ActionShowDownJob::class : SeparateBankJob::class;
    }
}
