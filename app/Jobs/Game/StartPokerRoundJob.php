<?php

namespace App\Jobs\Game;

use App\Repositories\PokerTableRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StartPokerRoundJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private PokerTableRepository $repository;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $tableId)
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
        $this->repository->createRound()
            ->save();
    }
}
