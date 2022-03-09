<?php

namespace App\Game\Collections;

use \App\Abstracts\AbstractGameCollection;
use App\Game\Player;

class PlayersCollection extends AbstractGameCollection
{
    /**
     * @param int $index
     * @return Player
     */
    public function get(int $index): object
    {
        return parent::get($index);
    }

    public function sortByPlaces(): PlayersCollection
    {
        $collection = [];
        /**
         * @var Player $item
         */
        foreach ($this->collection as $item){
            $collection[$item->getPlace()] = $item;
        }

        ksort($collection);

        $this->collection = $collection;

        return $this;
    }

    public function getWithStrongestHand(): Player
    {
        $collection  = [];

        foreach ($this->collection as $player) {
            /**
             * @var Player $player
             */
            $combo =  $player->getCombo();
            $collection[$combo->getComboIndex()][$combo->getHighCardIndex()][$combo->getComboMeterCardIndex()][] = $player;
        }

        ksort($collection,2);dump($collection);
        $strong = array_slice($collection,0,1);dump($strong);
        ksort($strong);
        $strongest = array_slice($strong,0,1);dump($strongest);
        ksort($strong);
        dd(array_slice($strongest,0,1));
        return $player;
    }
}
