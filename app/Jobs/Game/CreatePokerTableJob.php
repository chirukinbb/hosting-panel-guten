<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Events\Game\Broadcasters\CreatePokerTableBroadcaster;
use App\Game\Player;
use App\Repositories\PokerTableRepository;

class CreatePokerTableJob extends AbstractGameJob
{
    protected string $broadcasterClass = CreatePokerTableBroadcaster::class;
    protected string $nextJobClass = StartPokerRoundJob::class;
    protected string $slug =  'turn';

    public function action(): PokerTableRepository
    {
        return $this->repository->createTable();
    }

    protected function eachPlayerFunc(Player $player)
    {
        broadcast(new $this->broadcasterClass(
            $this->repository->getTableId(),
            $this->screen,
            $this->slug.'.'.$player->getUserId(),
            $player->getUserId()
        ));
    }
}
