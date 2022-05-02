<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\FlopBroadcaster;
use App\Repositories\PokerTableRepository;

class SeparateBankJob extends AbstractGameJob
{
    protected string $broadcasterClass = FlopBroadcaster::class;
    protected string $nextJobClass = StartPokerRoundJob::class;

    public function handle()
    {
        $this->setNextJobClass();

        parent::handle();
    }

    public function action(): PokerTableRepository
    {
        return $this->repository->payments();
    }

    public function setNextJobClass(): void
    {
        $this->nextJobClass = $this->repository->isNewRound() ?
            StartPokerRoundJob::class : FinishTableJob::class;
    }
}
