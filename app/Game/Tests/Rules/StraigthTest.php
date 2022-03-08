<?php

namespace App\Game\Tests\Rules;

use App\Game\Card;
use App\Game\Collections\CardsCollection;
use App\Game\CombosChecker;

class StraigthTest
{
    protected string $comboName = 'стрит';

    public function __construct()
    {
        $this->comboExist();
        $this->comboNotExist();
    }

    public function comboExist()
    {
        $cardsPool = new CardsCollection();

        $cardsPool->push(new Card(2,2,1,'2','s'));
        $cardsPool->push(new Card(5,2,2,'5','s'));
        $cardsPool->push(new Card(3,0,3,'n','s'));
        $cardsPool->push(new Card(9,2,4,'9','s'));
        $cardsPool->push(new Card(2,2,5,'n','s'));
        $cardsPool->push(new Card(6,3,6,'t','s'));
        $cardsPool->push(new Card(4,2,7,'n','s'));

        $checker = new CombosChecker($cardsPool);

        echo 'Есть '.$this->comboName.': '.(int)!!$checker->check('straight').PHP_EOL;
    }

    public function comboNotExist()
    {
        $cardsPool = new CardsCollection();

        $cardsPool->push(new Card(2,2,1,'2','s'));
        $cardsPool->push(new Card(5,2,2,'5','s'));
        $cardsPool->push(new Card(2,0,3,'n','s'));
        $cardsPool->push(new Card(9,0,4,'9','s'));
        $cardsPool->push(new Card(2,2,5,'n','s'));
        $cardsPool->push(new Card(10,3,6,'t','s'));
        $cardsPool->push(new Card(4,2,7,'n','s'));

        $checker = new CombosChecker($cardsPool);

        echo 'Нет ' . $this->comboName . ': ' . (int)$checker->check('straight');
    }
}
