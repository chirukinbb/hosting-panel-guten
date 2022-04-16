<?php

namespace App\Jobs\Game;

use App\Repositories\PokerTableRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FinishPlayerTurnJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected PokerTableRepository $repository;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected int $tableId)
    {
        $this->repository = new PokerTableRepository($tableId);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->repository->entTimeForTurn()->save();

        if ($this->repository->isTurnTransfer()){
            dispatch(new StartAuctionForPlayerJob(
                $this->tableId,
                'table'
            ));
        } elseif ($this->repository->isShowDown()){
            dispatch(new StartPokerRoundJob(
                $this->tableId,
                'table'
            ));
        }elseif ($this->repository->isTableFinish()){
            dispatch(new FinishTableJob(
                $this->tableId,
                'table'
            ));
        }
    }
}
