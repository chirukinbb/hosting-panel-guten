<?php

namespace App\Http\Controllers;

class HoldemNinePokerDeck extends \App\Abstracts\AbstractPokerDeck
{
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
        return 9;
    }

    protected function getBlind()
    {
        return 10;
    }
}
