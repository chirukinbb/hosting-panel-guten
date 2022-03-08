<?php

namespace App\Game\Collections;

use App\Abstracts\AbstractGameCollection;

class NominalCombinedCardsCollection extends CardsCollection
{
    protected int $nominalId;

    /**
     * @param int $nominalId
     */
    public function setNominalId(int $nominalId): void
    {
        $this->nominalId = $nominalId;
    }

    public function push(object $obj): void
    {
        if (! isset($this->collection[$this->nominalId])) {
            $this->collection[$this->nominalId] = new CardsCollection();
        }

        $this->collection[$this->nominalId]->push($obj);
    }

    /**
     * @param int $index
     * @return CardsCollection
     */
    public function get(int $index): object
    {
        return parent::get($index)->get(0);
    }

    public function sortByNominalId()
    {
        /**
         * @var CardsCollection $cards
         */
        foreach ($this->collection as $cards) {
            $this->collection[$cards->get(0)->getNominalIndex()] = $cards;
        }

        ksort($this->collection,2);

        return $this;
    }
}
