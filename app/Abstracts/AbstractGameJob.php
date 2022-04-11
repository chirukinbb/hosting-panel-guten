<?php

namespace App\Abstracts;

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
    protected string $broadcasterClass;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public int    $tableId,
        public string $screen
    )
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
        $this->action()->save();
        $this->repository->eachPlayer([$this,'eachPlayerFunc']);
    }

    abstract public function  action(): PokerTableRepository;

    protected function eachPlayerFunc(Player $player)
    {
        broadcast(new $this->broadcasterClass(// оповещение о дейстии в клиет игры
            $this->tableId,
            $this->screen,
            $this->repository->getChannelName('table', $player->getUserId()),
            $player->getUserId()
        ));
    }
}
