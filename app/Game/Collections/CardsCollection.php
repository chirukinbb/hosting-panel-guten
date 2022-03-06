<?php

namespace App\Game\Collections;

use App\Abstracts\AbstractGameCollection;
use App\Game\Card;

class CardsCollection extends AbstractGameCollection
{
    /**
     * @param int $index
     * @return Card
     */
    public function get(int $index): object
    {
        return parent::get($index);
    }

    public function removeFirsts(int $count): CardsCollection
    {
        $this->collection = array_slice($this->collection,$count);

        return $this;
    }
}
