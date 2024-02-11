<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\FinishPlayerFlopAuctionBroadcaster;
use App\Events\Game\Broadcasters\FinishPlayerPreFlopAuctionBroadcaster;
use App\Events\Game\Broadcasters\FinishPlayerRiverAuctionBroadcaster;
use App\Events\Game\Broadcasters\FinishPlayerTurnAuctionBroadcaster;
use App\Events\PokerTableStateEvent;
use App\Game\Player;
use App\Models\Game\Table;
use App\Repositories\PokerTableRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FinishPlayerRoundOfBettingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private PokerTableRepository $repository;

    public function __construct(int $tableId, private int $activePlayerId, private int $roundNumber)
    {
        $this->repository = new PokerTableRepository($tableId);
    }

    public function handle()
    {

        if ($this->repository->getRoundNumber() === $this->roundNumber  && $this->repository->getActivePlayerId() === $this->activePlayerId){
            $this->repository->entTimeForAction();

            $this->repository->eachPlayer(function (Player $player) {
                broadcast(new PokerTableStateEvent(Table::find($this->repository->getTableId()),$player->getUserId()));
            });

            dispatch(new ($this->nextJobClass())($this->repository->getTableId()))
                ->delay(now()->addSeconds(3));
        }
    }

    public function nextJobClass(): string
    {
        return $this->repository->isExtractBidsToBank() ?
            ($this->repository->isNewLoopWithoutBid() ?  : BankCalculatingJob::class)
            : StartPlayerRoundOfBettingJob::class;
    }
}
