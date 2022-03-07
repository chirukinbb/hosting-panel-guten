<?php

namespace App\Game\Collections;

use App\Abstracts\AbstractGameCollection;
use App\Game\Card;

class CardsCollection extends AbstractGameCollection
{
    protected Card $result;
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

    public function getHighCard(array $excludedNominalIds,$isExcluded): Card
    {
        $highCardNominalIndex = -1;
        $highCard  = null;
        /**
         * @var Card $card
         */
        foreach ($this->collection as $card){
            $exclude =  $isExcluded ?
                !in_array($card->getNominalIndex(),$excludedNominalIds) :
                in_array($card->getNominalIndex(),$excludedNominalIds);

            if ($card->getNominalIndex() > $highCardNominalIndex && $exclude) {
                $highCardNominalIndex = $card->getNominalIndex();
                $highCard = $card;
            }
        }

        return $highCard;
    }

    public function sortByNominal()
    {
        /**
         * @var Card $card
         */
        foreach ($this->collection as $card) {
            $this->collection[$card->getNominalIndex()] = $card;
        }

        ksort($this->collection);

        return $this->collection;
    }
}
