<?php

namespace App\Http\Controllers;

use App\Builders\PokerTableBuilder;
use App\Events\Game\Broadcasters\FlopBroadcaster;
use App\Events\Game\Broadcasters\PreFlopBroadcaster;
use App\Events\Game\Broadcasters\StartPlayerAuctionAfterFlopBroadcaster;
use App\Events\Game\Broadcasters\StartPokerRoundBroadcaster;
use App\Events\Game\Broadcasters\TurnBroadcaster;
use App\Game\Player;
use App\Game\Tables\HoldemTwoPokerTable;
use App\Jobs\Game\FinishTableJob;
use App\Repositories\PokerTableRepository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Queue;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(): \Illuminate\Contracts\Support\Renderable
    {
//        $table  = new HoldemNinePokerTable(1);
//        $table->startRound();
//
//        for ($i=0;$i<9;$i++) {
//            $table->setPlayer($i, 'gg', 'fff');
//        }
//
//        $table->eachPlayer(function (Player $player){
//            $player->addToBid($player->getUserId() * 10 + 100,0);
//
//            if (($player->getUserId() % 3) === 0 || $player->getUserId()  ===8)
//            $player->setInRound(true);
//            else
//                $player->setInRound(false);
//
//        });

//        $table->bidsToBank();
//        $table->payToWinners();
//        dd($table);
//        $repo = new PokerTableBuilder(7,17);
////
////        $repo->createRound()->setCurrentStepInRound(0)->startTimer()->save(StartPlayerAuctionAfterFlopBroadcaster::class);
//        dump(auth()->id(),$repo);
//dd(
//    call_user_func([new StartPlayerAuctionAfterFlopBroadcaster(
//    7,
//    'table',
//        'hhhhhh',
//        18
//),'broadcastWith'])
//);

        return view('home');
    }
}
