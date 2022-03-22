<?php

namespace App\Http\Controllers;

use App\Game\Tables\HoldemTwoPokerTable;
use App\Models\Game\Table;

class GameController extends Controller
{
    protected array $pokerTables = [
        'holdem_2'=>HoldemTwoPokerTable::class,
        'omaha'=>''
    ];

    public function index()
    {//dd(Table::find(17)->object);
        $token = \Auth::user()->createApiToken();

        return view('game', compact('token'));
    }
}
