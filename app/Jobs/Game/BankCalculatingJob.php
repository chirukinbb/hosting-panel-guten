<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\ExtractBidsToBankFlopBroadcaster;
use App\Events\Game\Broadcasters\ExtractBidsToBankPreFlopBroadcaster;
use App\Events\Game\Broadcasters\ExtractBidsToBankRiverBroadcaster;
use App\Events\Game\Broadcasters\ExtractBidsToBankTurnBroadcaster;
use App\Repositories\PokerTableRepository;

class BankCalculatingJob extends AbstractGameJob
{
    public function action(): PokerTableRepository
    {
        return $this->repository->bidsToBank();
    }

    public function handle()
    {
        $this->setNextJobClass();
        $this->setBroadcasterClass();

        parent::handle();
    }

    public function setNextJobClass(): void
    {
        if ($this->repository->isShowDown())
            $this->nextJobClass = AllInShowDownJob::class;
        else
            $this->nextJobClass = match ($this->repository->getCurrentStepInRound()) {
                1 => FlopPokerJob::class,
                2 => TurnPokerJob::class,
                3 => ShowdownPokerJob::class
            };
    }

    public function setBroadcasterClass(): void
    {
        $this->broadcasterClass = match ($this->repository->getCurrentStepInRound()) {
            0 => ExtractBidsToBankPreFlopBroadcaster::class,
            1 => ExtractBidsToBankFlopBroadcaster::class,
            2 => ExtractBidsToBankTurnBroadcaster::class,
            3 => ExtractBidsToBankRiverBroadcaster::class,
        };
    }
}
