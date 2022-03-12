<?php

namespace App\Game\Tables;

use App\Abstracts\AbstractPokerDeck;

class HoldemTwoPokerDeck extends AbstractPokerDeck
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

    protected function getPlayersCount()
    {
        return self::$count;
    }

    protected function getBlind()
    {
        return 10;
    }
}
