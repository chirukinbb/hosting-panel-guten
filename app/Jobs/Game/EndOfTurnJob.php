<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\CreatePokerTableBroadcaster;
use App\Repositories\PokerTableRepository;

class EndOfTurnJob extends AbstractGameJob
{
    protected string $broadcasterClass = CreatePokerTableBroadcaster::class;
    protected string $nextJobClass = StartPokerRoundJob::class;

    public function __construct(
        public int|string $classNameOrTableId,
        public string     $screen
    )
    {
        parent::__construct($this->classNameOrTableId,$this->screen);
        $this->nextJobClass = $this->getNextJobClass();
    }

    public function action(): PokerTableRepository
    {
        return $this->repository->roundFinish();
    }

    protected function getNextJobClass()
    {
        return $this->repository->isNewRound() ?
            StartPokerRoundJob::class :
            FinishTableJob::class;
    }
}
