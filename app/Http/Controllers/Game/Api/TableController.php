<?php

namespace App\Http\Controllers\Game\Api;

use App\Http\Controllers\Controller;
use App\Models\Game\Player;
use App\Repositories\PokerTableRepository;
use Illuminate\Http\Request;

class TableController extends Controller
{
    protected PokerTableRepository $repository;

    public function __construct()
    {
        $player = Player::whereUserId(auth()->id())
            ->whereNotNull('gamed')
            ->first('gamed');
        $this->repository = new PokerTableRepository($player->gamed);
    }

    public function turnAction(Request $request)
    {
        if ($request->input('action_id'))
            call_user_func(
                [$this, 'action'.$request->input('action_id')],
                $request->input('amount')
            );
    }

    public function showDownAction()
    {}

    public function leave(Request $request)
    {
        return json_encode(['screen'=>'list']);
    }

    protected function action0()
    {
        $this->repository->fold();
    }

    protected function action1()
    {
        $this->repository->check();
    }

    protected function action2(int $amount)
    {
        $this->repository->raise($amount);
    }

    protected function action3()
    {
        $this->repository->allIn();
    }

    protected function action4()
    {
        $this->repository->call();
    }
}
