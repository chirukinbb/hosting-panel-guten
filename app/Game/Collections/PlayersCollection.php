<?php

namespace App\Game\Collections;

use \App\Abstracts\AbstractGameCollection;
use App\Game\Player;
use Ratchet\ConnectionInterface;

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

    public function getWithStrongestHand(): array
    {
        $collection  = [];

        foreach ($this->collection as $player) {
            /**
             * @var Player $player
             */
            $combo =  $player->getCombo();
            $collection[$combo->getComboIndex()][$combo->getHighCardIndex()][$combo->getComboMeterCardIndex()][] = $player->getPlace();
        }

        $strong = $collection[max(array_keys($collection))];
        $strongest = $strong[max(array_keys($strong))];

        return $strongest[max(array_keys($strongest))];
    }

    public function removeWhereObj(ConnectionInterface $player): void
    {
        unset($player);
    }
}
