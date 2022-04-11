<?php

namespace App\Game\Tables;

use App\Abstracts\AbstractPokerTable;

class HoldemTwoPokerTable extends AbstractPokerTable
{
    public static int $count = 2;
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
        return self::$count;
    }

    public function getBlind()
    {
        return 10;
    }

    public function getType()
    {
        return 'Holdem';
    }
}
