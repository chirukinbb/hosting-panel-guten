<?php

namespace App\Http\Controllers\Game\Api;

use App\Game\Tables\HoldemTwoPokerTable;
use App\Http\Controllers\Controller;

class LobbyController extends Controller
{
    public function list()
    {
        return response()->json(['data' => [HoldemTwoPokerTable::class => HoldemTwoPokerTable::POKER_TYPE . ' ' . HoldemTwoPokerTable::PLAYER_COUNT . ' players']]);
    }
}
