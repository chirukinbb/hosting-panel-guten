<?php

namespace App\Abstracts;

use App\Events\PokerTableStateEvent;
use App\Game\Player;
use App\Repositories\PokerTableRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

abstract class AbstractGameJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected PokerTableRepository $repository;
    protected string $nextJobClass;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $tableId)
    {
        $this->repository = PokerTableRepository::instance($tableId);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->action();

        $this->repository->eachPlayer(function (Player $player) {
            // оповещение о состоянии стола в клиет игры
            broadcast(new PokerTableStateEvent($this->repository->getTableObject(), $player->getUserId()));
        });

        if ($this->nextJobClass)
            dispatch(new $this->nextJobClass($this->repository->getTableId()));
    }

    abstract public function action();
}
