<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\AuctionPokerRoundBroadcaster;
use App\Events\Game\Broadcasters\FlopAuctionResultBroadcaster;
use App\Events\Game\Broadcasters\PreFlopAuctionResultBroadcaster;
use App\Events\Game\Broadcasters\RiverAuctionResultBroadcaster;
use App\Events\Game\Broadcasters\StartPlayerAuctionAfterFlopBroadcaster;
use App\Events\Game\Broadcasters\StartPlayerAuctionAfterPreFlopBroadcaster;
use App\Events\Game\Broadcasters\StartPlayerAuctionAfterRiverBroadcaster;
use App\Events\Game\Broadcasters\StartPlayerAuctionAfterTurnBroadcaster;
use App\Events\Game\Broadcasters\TurnAuctionResultBroadcaster;
use App\Repositories\PokerTableRepository;
use Illuminate\Support\Facades\Queue;

class StartAuctionForPlayerJob extends AbstractGameJob
{
    public function action(): PokerTableRepository
    {
        return $this->repository->startTimer();
    }

    public function handle()
    {
        $this->setBroadcasterClass();

        $this->removedJobId = Queue::later(
            now()->addSeconds($this->repository->getTimeOnTurn()),
            (string)new FinishPlayerAuctionJob($this->classNameOrTableId, 'table')
        );

        parent::handle();
    }

    protected function setBroadcasterClass()
    {
        $this->broadcasterClass = match ($this->repository->getCurrentStepInRound()) {
            0 => StartPlayerAuctionAfterPreFlopBroadcaster::class,
            1 => StartPlayerAuctionAfterFlopBroadcaster::class,
            2 => StartPlayerAuctionAfterTurnBroadcaster::class,
            3 => StartPlayerAuctionAfterRiverBroadcaster::class
        };
    }
}
