<?php

namespace App\Http\Controllers;

use App\Repositories\Admin\UserRepository;

class HomeController extends Controller
{
    public function index()
    {
        $pocker = new HoldemNinePokerDeck(range(5,14));
        $pocker->setId(1);
        $pocker->startRound(1);
        dd($pocker);
        return view('home');
    }
}
