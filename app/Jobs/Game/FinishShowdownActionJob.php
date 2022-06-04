<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\FinishShowdownActionBroadcaster;
use App\Repositories\PokerTableRepository;
use Illuminate\Support\Facades\Queue;

class FinishShowdownActionJob extends AbstractGameJob
{
    public function handle()
    {
        $this->setNextJobClass();

        parent::handle();
    }

    public function action(): PokerTableRepository
    {
        return $this->repository->showdownPlayerActions();
    }

    public function setNextJobClass()
    {
        if ($this->repository->isNextPlayerShowdownAction())
            $this->nextJobClass = ActionShowDownJob::class;
        else
            $this->nextJobClass = SeparateBankJob::class;
    }
}
