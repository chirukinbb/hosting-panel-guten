<?php

namespace App\Jobs\Game;

use App\Abstracts\AbstractGameJob;
use App\Abstracts\AbstractPokerTable;
use App\Events\Game\Broadcasters\CreatePokerTableBroadcaster;
use App\Events\PokerTableStateEvent;
use App\Game\Player;
use App\Models\Game\Table;
use App\Repositories\PokerTableRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreatePokerTableJob implements ShouldQueue
{
    public function __construct(protected string $className)
    {
    }

    public function handle()
    {
        $repository = PokerTableRepository::instance($this->className);
        $repository->createTable();

        $repository->eachPlayer(function (Player $player) use ($repository){
            broadcast(new PokerTableStateEvent($repository->getTableObject(),$player->getUserId()));
        });

        dispatch(new StartPokerRoundJob($repository->getTableId()));
    }
}
