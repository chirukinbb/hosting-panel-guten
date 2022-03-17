<?php

namespace App\Http\Controllers;

use App\Game\Tables\HoldemTwoPokerDeck;

class GameController extends Controller
{
    protected array $pokerTables = [
        'holdem_2'=>HoldemTwoPokerDeck::class,
        'omaha'=>''
    ];

    public function index()
    {
        return view('game', ['tables'=>$this->pokerTables]);
    }
}
