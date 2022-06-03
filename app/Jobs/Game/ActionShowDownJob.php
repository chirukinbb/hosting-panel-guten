<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\ActionShowDownBroadcaster;
use App\Repositories\PokerTableRepository;

class ActionShowDownJob extends AbstractGameJob
{
    protected string $broadcasterClass = ActionShowDownBroadcaster::class;

    public function handle()
    {
        $this->setNextJobClass();

        dispatch(new FinishShowdownActionJob($this->classNameOrTableId,'table'))
            ->delay(now()->addSeconds(5));

        parent::handle();
    }

    public function action(): PokerTableRepository
    {
        return $this->repository->showdownPlayerActions();
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
