<?php

namespace App\Game\Collections;

class SuitCombinedCardsCollection extends CardsCollection
{
    protected int $suitId;

    /**
     * @param int $suitId
     */
    public function setSuitId(int $suitId): void
    {
        $this->suitId = $suitId;
    }

    public function push(object $obj): SuitCombinedCardsCollection
    {
        if (! isset($this->collection[$this->suitId])) {
            $this->collection[$this->suitId] = new CardsCollection();
        }

        $this->collection[$this->suitId]->push($obj);

        return $this;
    }

    /**
     * @param int $index
     * @return CardsCollection
     */
    public function get(int $index): object
    {
        return parent::get($index)->get(0);
    }
}
