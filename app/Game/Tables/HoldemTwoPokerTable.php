<?php

namespace App\Game\Tables;

use App\Abstracts\AbstractPokerTable;

class HoldemTwoPokerTable extends AbstractPokerTable
{
    public static int $count;

    protected function getMinNominal()
    {
        return 1;
    }

    protected function getCardsInHand()
    {
        return 2;
    }

    public function getPlayersCount()
    {
        return self::$count;
    }

    protected function getBlind()
    {
        return 10;
    }
}
