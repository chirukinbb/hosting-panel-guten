<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\FlopBroadcaster;
use App\Repositories\PokerTableRepository;

class SeparateBankJob extends AbstractGameJob
{
    protected string $broadcasterClass = FlopBroadcaster::class;

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
        if ($this->repository->isNewRound())
            $this->nextJobClass = StartPokerRoundJob::class;
        elseif($this->repository->isPrevTimeShowdown())
            $this->nextJobClass = match ($this->repository->getCurrentStepInRound()) {
                0 => FlopPokerJob::class,
                1 => TurnPokerJob::class,
                2 => RiverPokerJob::class
            };
        elseif ($this->repository->existsLosers())
            $this->nextJobClass = GameOverPokerJob::class;
        else
            $this->nextJobClass = FinishTableJob::class;
    }
}
