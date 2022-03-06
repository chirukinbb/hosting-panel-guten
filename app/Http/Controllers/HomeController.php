<?php

namespace App\Http\Controllers;

use App\Game\Card;
use App\Repositories\Admin\UserRepository;
use Ratchet\Session\Storage\VirtualSessionStoragePDOTest;

class HomeController extends Controller
{
    public function index()
    {
        $pocker = new HoldemNinePokerDeck(range(5,14));
        $pocker->setId(1);
        $pocker->startRound(1);
        $pocker->changeStatuses(8);
        $pocker->changeStatuses(0);
        $pocker->preFlop();
        $pocker->flop();
        $pocker->turn();
        $pocker->river();
        dd($pocker);
        return view('home');
    }
}
