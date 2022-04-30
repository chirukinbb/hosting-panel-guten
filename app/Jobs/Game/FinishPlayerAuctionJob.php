<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\EndOfFlopLoopBroadcaster;
use App\Events\Game\Broadcasters\EndOfPreFlopLoopBroadcaster;
use App\Events\Game\Broadcasters\EndTimeOnRiverAuctionBroadcaster;
use App\Events\Game\Broadcasters\EndTimeOnTurnAuctionBroadcaster;
use App\Events\Game\Broadcasters\FlopAuctionResultBroadcaster;
use App\Events\Game\Broadcasters\PreFlopAuctionResultBroadcaster;
use App\Events\Game\Broadcasters\RiverAuctionResultBroadcaster;
use App\Events\Game\Broadcasters\TurnAuctionResultBroadcaster;
use App\Repositories\PokerTableRepository;

class FinishPlayerAuctionJob extends AbstractGameJob
{
    public function action(): PokerTableRepository
    {
        return $this->repository->entTimeForAction();
    }

    public function handle()
    {
        $this->setNextJobClass();
        $this->setBroadcasterClass();

        parent::handle();
    }

    public function setNextJobClass(): void
    {
        if ($this->repository->isNewLoop())
            $this->nextJobClass = match ($this->repository->getCurrentStepInRound()) {
                1 => FlopPokerJob::class,
                2 => TurnPokerJob::class,
                3 => RiverPokerJob::class
            };
        elseif ($this->repository->isShowDown())
            $this->nextJobClass = AllInShowDownJob::class;
        else
            $this->nextJobClass = StartAuctionForPlayerJob::class;
    }

    public function setBroadcasterClass(): void
    {
        $this->broadcasterClass = match ($this->repository->getCurrentStepInRound()) {
            0 => EndOfPreFlopLoopBroadcaster::class,
            1 => EndOfFlopLoopBroadcaster::class,
            2 => EndTimeOnTurnAuctionBroadcaster::class,
            3 => EndTimeOnRiverAuctionBroadcaster::class,
        };
//        $this->broadcasterClass = match ($this->repository->getCurrentStepInRound()) {
//            0 => PreFlopAuctionResultBroadcaster::class,
//            1 => FlopAuctionResultBroadcaster::class,
//            2 => TurnAuctionResultBroadcaster::class,
//            3 => RiverAuctionResultBroadcaster::class,
//        };
    }
}
