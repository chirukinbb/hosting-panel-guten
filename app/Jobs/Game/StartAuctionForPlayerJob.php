<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\AuctionPokerRoundBroadcaster;
use App\Events\Game\Broadcasters\StartPlayerAuctionAfterPreFlopBroadcaster;
use App\Events\Game\Broadcasters\StartPlayerAuctionAfterRiverBroadcaster;
use App\Events\Game\Broadcasters\StartPlayerAuctionAfterTurnBroadcaster;
use App\Repositories\PokerTableRepository;

class StartAuctionForPlayerJob extends AbstractGameJob
{
    private array $steps = [
        StartPlayerAuctionAfterPreFlopBroadcaster::class,
        StartPlayerAuctionAfterPreFlopBroadcaster::class,
        StartPlayerAuctionAfterTurnBroadcaster::class,
        StartPlayerAuctionAfterRiverBroadcaster::class
    ];

    public function action(): PokerTableRepository
    {
        return $this->repository->startTimer();
    }

    public function handle()
    {
        $this->setBroadcasterClass();

        dispatch(new FinishPlayerTurnJob($this->classNameOrTableId))
            ->delay(now()->addSeconds($this->repository->getTimeOnTurn()));// ставим задачу на прекращение хода

        parent::handle();
    }

    protected function setBroadcasterClass()
    {
        $this->broadcasterClass =  $this->steps[$this->repository->getCurrentStepInRound()];
    }
}
