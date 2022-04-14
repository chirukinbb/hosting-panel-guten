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

    public function getFirsts(int $count): CardsCollection
    {
        $collection = clone $this;
        $index = 1;

        $collection->each(function ()  use ($collection,&$index,$count) {
            if ($index > $count)
                $collection->remove($index - 1);
        });

        return $collection;
    }

    public function getHighCard(array $excludedNominalIds,$isExcluded): Card|bool
    {
        $highCardNominalIndex = -1;
        $highCard  = false;
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

    public function getLowCard(array $excludedNominalIds,$isExcluded): Card
    {
        $lowCardNominalIndex = 15;
        $lowCard  = null;
        /**
         * @var Card $card
         */
        foreach ($this->collection as $card){
            $exclude =  $isExcluded ?
                !in_array($card->getNominalIndex(),$excludedNominalIds) :
                in_array($card->getNominalIndex(),$excludedNominalIds);

            if(($cardIndex = $card->getNominalIndex()) === 13 && in_array(1,$excludedNominalIds)){
                return $card;
            }

            if ($cardIndex < $lowCardNominalIndex && $exclude) {
                $lowCardNominalIndex = $card->getNominalIndex();
                $lowCard = $card;
            }
        }

        return $lowCard;
    }

    public function sortByNominal(): static
    {
        $collection = [];

        /**
         * @var Card $card
         */
        foreach ($this->collection as $index => $card) {
            $collection[$card->getNominalIndex()] = $card;
        }

        ksort($collection);
        $this->collection = $collection;

        return $this;
    }
}
