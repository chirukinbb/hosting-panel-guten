<?php

namespace App\Game\Tests\Rules;

use App\Game\Card;
use App\Game\Collections\CardsCollection;
use App\Game\CombosChecker;

class RoyalStraightTest
{
    protected string $comboName = 'роял-флэш';

    public function __construct()
    {
        $this->comboExist();
        $this->comboNotExist();
    }

    public function comboExist()
    {
        $cardsPool = new CardsCollection();

        $cardsPool->push(new Card(9,3,1,'n','s'));
        $cardsPool->push(new Card(12,3,2,'n','s'));
        $cardsPool->push(new Card(4,2,3,'n','s'));
        $cardsPool->push(new Card(6,2,4,'6','s'));
        $cardsPool->push(new Card(11,3,5,'n','s'));
        $cardsPool->push(new Card(10,3,6,'t','s'));
        $cardsPool->push(new Card(13,3,7,'n','s'));

        $checker = new CombosChecker($cardsPool);

        echo 'Есть '.$this->comboName.': '.(int)!!$checker->check('straightFlush').PHP_EOL;
    }

    public function comboNotExist()
    {
        $cardsPool = new CardsCollection();

        $cardsPool->push(new Card(2,2,1,'n','s'));
        $cardsPool->push(new Card(5,2,2,'n','s'));
        $cardsPool->push(new Card(4,2,3,'n','s'));
        $cardsPool->push(new Card(9,2,4,'6','s'));
        $cardsPool->push(new Card(3,2,5,'n','s'));
        $cardsPool->push(new Card(10,3,6,'t','s'));
        $cardsPool->push(new Card(2,3,7,'n','s'));

        $checker = new CombosChecker($cardsPool);

        echo 'Нет '.$this->comboName.': '.(int)$checker->check('straightFlush');
    }
}
