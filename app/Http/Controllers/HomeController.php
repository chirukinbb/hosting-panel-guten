<?php

namespace App\Http\Controllers;

use App\Builders\PokerTableBuilder;
use App\Events\Game\Broadcasters\StartPokerRoundBroadcaster;
use App\Game\Player;
use App\Game\Tables\HoldemTwoPokerTable;
use App\Repositories\PokerTableRepository;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
//        $table  = new HoldemNinePokerTable(1);
//        $table->startRound();
//
//        for ($i=0;$i<9;$i++) {
//            $table->setPlayer($i, 'gg', 'fff');
//        }
//
//        $table->eachPlayer(function (Player $player){
//            $player->addToBid($player->getUserId() * 10 + 100);
//
//            if (($player->getUserId() % 3) === 0 || $player->getUserId()  ===8)
//            $player->setInRound(true);
//            else
//                $player->setInRound(false);
//
//        });
//
//        $table->bidsToBank();
//        $table->payToWinners();
//        dd($table);
        //$repo = new PokerTableBuilder(7,17);
       // $repo->createRound()->save();
       // dd($repo);
dd(
    call_user_func([new StartPokerRoundBroadcaster(
    7,
    'table',
        'hhhhhh',
        17
),'broadcastWith'])
);
        return view('home');
    }
}
