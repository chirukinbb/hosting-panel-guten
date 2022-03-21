<?php

namespace App\Http\Controllers;

use App\Game\Tables\HoldemTwoPokerTable;

class GameController extends Controller
{
    protected array $pokerTables = [
        'holdem_2'=>HoldemTwoPokerTable::class,
        'omaha'=>''
    ];

    public function index()
    {
        $token = \Auth::user()->createApiToken();

        return view('game', compact('token'));
    }
}
