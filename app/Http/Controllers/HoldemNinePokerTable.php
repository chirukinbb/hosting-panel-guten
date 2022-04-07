<?php

namespace App\Http\Controllers;

class HoldemNinePokerTable extends \App\Abstracts\AbstractPokerTable
{
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
        return 9;
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
