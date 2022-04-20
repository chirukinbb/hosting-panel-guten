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
    protected string $nextJobClass;
    protected string $slug = 'table';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public int|string $classNameOrTableId,
        public string     $screen
    )
    {
        $this->repository = is_int($this->classNameOrTableId) ?
            new PokerTableRepository($this->classNameOrTableId) :
            PokerTableRepository::instance($this->classNameOrTableId);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->action()->save($this->broadcasterClass);
        $this->repository->eachPlayer(function (Player $player) {
            call_user_func([$this,'eachPlayerFunc'],$player);
        });

        dispatch(new $this->nextJobClass($this->repository->getTableId(),$this->screen));
    }

    abstract public function action(): PokerTableRepository;

    protected function eachPlayerFunc(Player $player)
    {
        broadcast(new $this->broadcasterClass(// оповещение о дейстии в клиет игры
            $this->repository->getTableId(),
            $this->screen,
            $this->repository->getChannelName($this->slug, $player->getUserId()),
            $player->getUserId()
        ));
    }
}
