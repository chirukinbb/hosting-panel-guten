<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\EndOfFlopLoopBroadcaster;
use App\Events\Game\Broadcasters\EndOfPreFlopLoopBroadcaster;
use App\Events\Game\Broadcasters\EndOfRiverLoopBroadcaster;
use App\Events\Game\Broadcasters\EndOfTurnLoopBroadcaster;
use App\Repositories\PokerTableRepository;

class EndOfLoopJob extends AbstractGameJob
{
    protected string $nextJobClass = StartPokerRoundJob::class;

    public function handle()
    {
        $this->setNextJobClass();
        $this->setBroadcasterClass();

        parent::handle();
    }

    public function action(): PokerTableRepository
    {
        return $this->repository->lapFinish();
    }

    public function setNextJobClass(): void
    {
        if ($this->repository->isTurnTransfer())
            $this->nextJobClass =  StartAuctionForPlayerJob::class;
        elseif($this->repository->isShowDown())
            $this->nextJobClass = AllInShowDownJob::class;
        else
            $this->nextJobClass = StartPokerRoundJob::class;
    }

    public function setBroadcasterClass(): void
    {
        $this->broadcasterClass = match ($this->repository->getCurrentStepInRound()) {
            0 => EndOfPreFlopLoopBroadcaster::class,
            1 => EndOfFlopLoopBroadcaster::class,
            2 => EndOfTurnLoopBroadcaster::class,
            3 => EndOfRiverLoopBroadcaster::class
        };
    }
}
