<?php

namespace App\Game\Tests\Rules;

use App\Game\Card;
use App\Game\Collections\CardsCollection;
use App\Game\CombosChecker;

class HighCardTest
{
    protected string $comboName = 'Cтаршая';

    public function __construct()
    {
        $this->comboExist();
        $this->comboNotExist();
    }

    public function comboExist()
    {
        $cardsPool = new CardsCollection();

        $cardsPool->push(new Card(6,2,7,'n','s'));
        $cardsPool->push(new Card(2,2,1,'2','s'));
        $cardsPool->push(new Card(13,2,2,'A','s'));
        $cardsPool->push(new Card(8,0,3,'n','s'));
        $cardsPool->push(new Card(4,2,4,'9','s'));
        $cardsPool->push(new Card(5,2,5,'n','s'));
        $cardsPool->push(new Card(0,3,6,'t','s'));

        $checker = new CombosChecker($cardsPool);

        echo $checker->check('highCard')->getName().PHP_EOL;
    }

    public function comboNotExist()
    {
    }
}
