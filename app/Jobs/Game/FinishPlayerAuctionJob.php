<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\FlopAuctionResultBroadcaster;
use App\Events\Game\Broadcasters\PreFlopAuctionResultBroadcaster;
use App\Events\Game\Broadcasters\RiverAuctionResultBroadcaster;
use App\Events\Game\Broadcasters\TurnAuctionResultBroadcaster;
use App\Repositories\PokerTableRepository;

class FinishPlayerAuctionJob extends AbstractGameJob
{
    protected string $broadcasterClass = PreFlopAuctionResultBroadcaster::class;

    public function action(): PokerTableRepository
    {
        return $this->repository->entTimeForAction();
    }

    public function handle()
    {
        $this->setNextJobClass();

        parent::handle();
    }

    public function setNextJobClass(): void
    {
        $this->nextJobClass = $this->repository->isTurnTransfer() ?
            StartAuctionForPlayerJob::class : EndOfLoopJob::class;
    }

    public function setBroadcasterClass(): void
    {
        $this->broadcasterClass = match ($this->repository->getCurrentStepInRound()) {
            0 => PreFlopAuctionResultBroadcaster::class,
            1 => FlopAuctionResultBroadcaster::class,
            2 => TurnAuctionResultBroadcaster::class,
            3 => RiverAuctionResultBroadcaster::class,
        };
    }
}
