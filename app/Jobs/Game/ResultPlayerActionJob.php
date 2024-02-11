<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\EndOfFlopLoopBroadcaster;
use App\Events\Game\Broadcasters\EndOfPreFlopLoopBroadcaster;
use App\Events\Game\Broadcasters\EndTimeOnRiverAuctionBroadcaster;
use App\Events\Game\Broadcasters\EndTimeOnTurnAuctionBroadcaster;
use App\Game\Player;
use App\Repositories\PokerTableRepository;

class ResultPlayerActionJob extends AbstractGameJob
{
    public function action(): PokerTableRepository
    {
        return $this->repository;
    }

    public function handle()
    {
        $this->setNextJobClass();
        $this->setBroadcasterClass();

        $this->action()->save($this->removedJobId);

        $this->repository->eachPlayer(function (Player $player) {
            call_user_func([$this,'eachPlayerFunc'],$player);
        });

        dispatch(new $this->nextJobClass($this->repository->getTableId(),$this->screen))
            ->delay(now()->addSeconds(3));
    }

    public function setNextJobClass(): void
    {
        if ($this->repository->isExtractBidsToBank())
            $this->nextJobClass = BankCalculatingJob::class;
        else
            $this->nextJobClass = StartPlayerRoundOfBettingJob::class;
    }

    public function setBroadcasterClass(): void
    {
        $this->broadcasterClass = match ($this->repository->getCurrentStepInRound()) {
            0 => EndOfPreFlopLoopBroadcaster::class,
            1 => EndOfFlopLoopBroadcaster::class,
            2 => EndTimeOnTurnAuctionBroadcaster::class,
            3 => EndTimeOnRiverAuctionBroadcaster::class,
        };
    }
}
