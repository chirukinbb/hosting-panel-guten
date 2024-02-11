<?php

namespace App\Game\Tests\Winner;

use App\Game\Card;
use App\Game\Collections\CardsCollection;

class HighCard extends AbstractTwoPlayers
{

    protected function firstCard(): Card
    {
        return new Card(1,0,1,'2','s');
    }

    protected function secondCard(): Card
    {
        return new Card(13,0,1,'K','s');
    }

    protected function thirdCard(): Card
    {
        return new Card(12,0,1,'A','s');
    }

    protected function fourCard(): Card
    {
        return new Card(2,0,1,'3','s');
    }

    protected function cardsOnTable(): CardsCollection
    {
        $cards = new CardsCollection();

        $cards->push(new Card(9,0,1,'T','s'));
        $cards->push(new Card(7,0,1,'8','s'));
        $cards->push(new Card(4,2,1,'5','s'));
        $cards->push(new Card(11,3,1,'Q','s'));
        $cards->push(new Card(10,1,1,'J','s'));

        return $cards;
    }
}
