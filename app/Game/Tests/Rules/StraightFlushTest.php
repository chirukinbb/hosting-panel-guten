<?php

namespace App\Game\Tests\Rules;

use App\Game\Card;
use App\Game\Collections\CardsCollection;
use App\Game\CombosChecker;

class StraightFlushTest
{
    public function __construct()
    {
        $this->StraightFlushExist();
        $this->StraightFlushNotExist();
    }

    public function StraightFlushExist()
    {
        $cardsPool = new CardsCollection();

        $cardsPool->push(new Card(2,2,1,'n','s'));
        $cardsPool->push(new Card(5,2,2,'n','s'));
        $cardsPool->push(new Card(4,2,3,'n','s'));
        $cardsPool->push(new Card(6,2,4,'6','s'));
        $cardsPool->push(new Card(3,2,5,'n','s'));
        $cardsPool->push(new Card(10,3,6,'t','s'));
        $cardsPool->push(new Card(2,3,7,'n','s'));

        $checker = new CombosChecker($cardsPool);

        echo 'Есть стрит-флэш: '.(int)!!$checker->check('straightFlush').PHP_EOL;
    }

    public function StraightFlushNotExist()
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

        echo 'Нет стрит-флэш: '.(int)$checker->check('straightFlush');
    }
}
