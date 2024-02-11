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

    public function push(object $obj): NominalCombinedCardsCollection
    {
        if (! isset($this->collection[$this->nominalId])) {
            $this->collection[$this->nominalId] = new CardsCollection();
        }

        $this->collection[$this->nominalId]->push($obj);

        return $this;
    }

    /**
     * @param int $index
     * @return object|null
     */
    public function get(int $index): ?object
    {
        return parent::get($index)->get(0);
    }

    public function sortByNominalId(): static
    {
        /**
         * @var CardsCollection $cards
         */
        foreach ($this->collection as $cards) {
            $this->collection[$cards->get(0)->getNominalIndex()] = $cards;
        }

        ksort($this->collection);

        return $this;
    }
}
