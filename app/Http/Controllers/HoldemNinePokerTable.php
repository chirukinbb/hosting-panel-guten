<?php

namespace App\Http\Controllers;

class HoldemNinePokerTable extends \App\Abstracts\AbstractPokerTable
{
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
        return 9;
    }

    protected function getBlind()
    {
        return 10;
    }
}
