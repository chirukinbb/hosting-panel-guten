<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\FinishPlayerFlopAuctionBroadcaster;
use App\Events\Game\Broadcasters\FinishPlayerPreFlopAuctionBroadcaster;
use App\Events\Game\Broadcasters\FinishPlayerRiverAuctionBroadcaster;
use App\Events\Game\Broadcasters\FinishPlayerTurnAuctionBroadcaster;
use App\Game\Player;
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
            0 => FinishPlayerPreFlopAuctionBroadcaster::class,
            1 => FinishPlayerFlopAuctionBroadcaster::class,
            2 => FinishPlayerTurnAuctionBroadcaster::class,
            3 => FinishPlayerRiverAuctionBroadcaster::class,
        };
    }
}
