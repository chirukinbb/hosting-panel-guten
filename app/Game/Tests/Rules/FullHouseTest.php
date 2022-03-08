<?php

namespace App\Game\Tests\Rules;

use App\Game\Card;
use App\Game\Collections\CardsCollection;
use App\Game\CombosChecker;

class FullHouseTest
{
    protected string $comboName = 'фулл-хаус';

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
        $cardsPool->push(new Card(2,0,3,'n','s'));
        $cardsPool->push(new Card(10,2,4,'t','s'));
        $cardsPool->push(new Card(2,3,5,'n','s'));
        $cardsPool->push(new Card(10,3,6,'t','s'));
        $cardsPool->push(new Card(5,3,7,'n','s'));

        $checker = new CombosChecker($cardsPool);

        echo 'Есть '.$this->comboName.': '.(int)!!$checker->check('fullHouse').PHP_EOL;
    }

    public function comboNotExist()
    {
        $cardsPool = new CardsCollection();

        $cardsPool->push(new Card(2,2,1,'2','s'));
        $cardsPool->push(new Card(5,2,2,'5','s'));
        $cardsPool->push(new Card(2,0,3,'n','s'));
        $cardsPool->push(new Card(10,2,4,'t','s'));
        $cardsPool->push(new Card(2,3,5,'n','s'));
        $cardsPool->push(new Card(10,3,6,'t','s'));
        $cardsPool->push(new Card(10,0,7,'n','s'));

        $checker = new CombosChecker($cardsPool);

        echo 'Нет '.$this->comboName.': '.(int)$checker->check('fullHouse');
    }
}
