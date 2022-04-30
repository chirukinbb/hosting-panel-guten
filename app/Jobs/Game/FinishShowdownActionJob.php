<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\FinishShowdownActionBroadcaster;
use App\Repositories\PokerTableRepository;

class FinishShowdownActionJob extends AbstractGameJob
{
    protected string|null $broadcasterClass = FinishShowdownActionBroadcaster::class;

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
        if ($this->repository->isTableFinish())
            $this->nextJobClass = FinishTableJob::class;
        elseif ($this->repository->isNewRound())
            $this->nextJobClass = StartPokerRoundJob::class;
        else
            $this->nextJobClass = ActionShowDownJob::class;
    }
}
