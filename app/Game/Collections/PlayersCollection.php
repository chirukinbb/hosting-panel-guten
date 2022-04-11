<?php

namespace App\Game\Collections;

use \App\Abstracts\AbstractGameCollection;
use App\Game\Player;
use Illuminate\Support\Arr;
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

    public function sortFromDealer(): PlayersCollection
    {
        $collection = [];

        /**
         * @var Player $player
         */
        foreach ($this->collection as $index => $player) {
            if ($player->isDealer())
                $dealerIndex  = $index;
        }

        $collection = array_merge(
            array_slice($this->collection, $dealerIndex),
            array_slice($this->collection, 0, $dealerIndex)
        );

        $this->collection = $collection;

        return $this;
    }

    public function sortFromLB(): PlayersCollection
    {
        $collection = [];
        $dealerIndex = 0;

        /**
         * @var Player $player
         */
        foreach ($this->collection as $index => $player) {
            if ($player->isLB())
                $dealerIndex  = $index;
        }

        $collection = array_merge(
            array_slice($this->collection, $dealerIndex),
            array_slice($this->collection, 0, $dealerIndex)
        );

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

    public function removeWhereObj($player): void
    {
        unset($this->collection[array_search($player, $this->collection)]);
    }

    public function getDealerIndex()
    {
        $index = -1;

        foreach ($this->collection as $player) {
            /**
             * @var Player $player
             */
            if ($player->isDealer())
                $index = $player->getPlace();
        }

        return $index;
    }

    public function sortFromPlace(int $place): static
    {
        $collection = array_merge(
            array_slice($this->collection, $place),
            array_slice($this->collection, 0, $place)
        );

        $this->collection = $collection;

        return $this;
    }
}
