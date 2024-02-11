<?php

namespace App\Http\Controllers\Game\Api;

use App\Http\Controllers\Controller;
use App\Models\Game\Player;
use App\Repositories\PokerTableRepository;

class TableController extends Controller
{
    protected PokerTableRepository|null $repository;

    public function __construct()
    {
        $player = Player::whereUserId(auth()->id())
            ->whereNotNull('gamed')
            ->first();

        $this->repository = $player ? new PokerTableRepository($player->gamed) : null;
    }

    protected function fold()
    {
        $this->repository->fold();
    }

    protected function check()
    {
        $this->repository->check();
    }

    protected function raise(int $amount)
    {
        $this->repository->raise($amount);
    }

    protected function default()
    {
        $this->repository->default();
    }

    protected function call()
    {
        $this->repository->call();
    }
}
