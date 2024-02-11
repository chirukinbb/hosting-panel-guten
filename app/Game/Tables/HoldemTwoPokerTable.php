<?php

namespace App\Game\Tables;

use App\Abstracts\AbstractPokerTable;

class HoldemTwoPokerTable extends AbstractPokerTable
{
    const PLAYER_COUNT = 2;
    const POKER_TYPE = 'Holdem';
    protected int $timeOnTurn = 20;

    protected function getMinNominal()
    {
        return 1;
    }

    public function getCardsInHand()
    {
        return 2;
    }

    public function getPlayersCount()
    {
        return self::PLAYER_COUNT;
    }

    public function getBlind()
    {
        return 10;
    }

    public function getType()
    {
        return self::POKER_TYPE;
    }
}
