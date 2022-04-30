<?php

namespace App\Game\Collections;

use \App\Abstracts\AbstractGameCollection;
use App\Game\Player;
use Illuminate\Support\Arr;
use Ratchet\ConnectionInterface;

class PlayersCollection extends AbstractGameCollection
{
    /**
     * @var Player[]
     */
    protected array $collection;
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

        foreach ($this->collection as $item){
            $collection[$item->getPlace()] = $item;
        }

        ksort($collection);

        $this->collection = $collection;

        return $this;
    }

    public function sortFromDealer(): PlayersCollection
    {
        $dealerIndex  = null;

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
        $dealerIndex = 0;

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
            $combo =  $player->getCombo(3);
            $collection[$combo->getComboIndex()][$combo->getHighCardIndex()][$combo->getComboMeterCardIndex()][] = $player->getPlace();
        }

        $strong = $collection[max(array_keys($collection))];
        $strongest = $strong[max(array_keys($strong))];

        return $strongest[max(array_keys($strongest))];
    }

    public function getByHandPower()
    {
        $collection  = [];

        foreach ($this->collection as $player) {
            $combo =  $player->getCombo(3);
            $collection[$combo->getComboIndex()][$combo->getComboMeterCardIndex()][$combo->getHighCardIndex()][$player->getBid()] = $player;
            krsort($collection[$combo->getComboIndex()][$combo->getComboMeterCardIndex()][$combo->getHighCardIndex()]);
            krsort($collection[$combo->getComboIndex()][$combo->getComboMeterCardIndex()]);
            krsort($collection[$combo->getComboIndex()]);
        }

        krsort($collection);
        $players = [];

        foreach ($collection as $combos) {
            foreach ($combos as $combs){
                foreach ($combs as $playersWithCombo){
                    $players[] = $playersWithCombo;
                }
            }
        }

        return $players;
    }
    function getById($id)
    {
        foreach ($this->collection as $item){
            if ($item->getUserId()===$id)
                return $item;
        }
    }

    public function removeWhereObj($player): void
    {
        unset($this->collection[array_search($player, $this->collection)]);
    }

    public function getDealerIndex()
    {
        $index = -1;

        foreach ($this->collection as $player) {
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

    /**
     * расчет статусов игроков в раунде
     * возврат места за столом большого блаинда
     *
     * @return int
     */
    public function changeStatuses()
    {
        $currentDealerIndex = $this->getDealerIndex();
        $dealerAlreadySet = false;
        $statuses = [
            'Dealer',
            'LB',
            'BB'
        ];

        do {
            /**
             * @var Player $player
             */
            $currentDealerIndex = $this->cycle($currentDealerIndex + 1);
            $player = $this->collection[$currentDealerIndex];

            if (!$dealerAlreadySet)
                $player->setDealerStatus(false);

            $player->setLBStatus(false);
            $player->setBBStatus(false);

            if ($currentDealerIndex === $player->getPlace()) {
                if ($player->isInGame()){
                    call_user_func([$player,'set'.array_shift($statuses).'Status'],true);
                    $dealerAlreadySet = true;
                }
            }
        } while (!empty($statuses));

        return $player->getPlace();
    }

    /**
     * расчитать размер награды для
     * игроков поставивших
     * разные ставки, но находятся в игре
     * с нуля
     */
    public function calculateBidBordersAbsolute()
    {
        $bids = [];

        foreach ($this->collection as $item) {
            if ($item->isInRound())
                $bids[] = $item->getBid();
        }

        $bids = array_unique($bids);
        sort($bids);

        return $bids;
    }

    /**
     * расчитать размер награды для
     * игроков поставивших
     * разные ставки, но находятся в игре
     * относительно следующей границы
     */
    public function calculateBidBordersRelative(array $absBorders)
    {
        $count = count($absBorders);
        $i = 0;

        do {
            $bank[$i] = array_shift($absBorders);

            foreach ($absBorders as &$bid) {
                $bid -= $bank[$i];
            }

            $i  ++;
        } while ($count !== count($bank));

        return $bank;
    }

    protected function cycle(int $place)
    {
        return $place % $this->count();
    }

    public function getNextActivePlayer(int $currentPlayerPlace): Player|bool
    {
        $i = $currentPlayerPlace + 1;

        do{
            $i = $this->cycle($i);
            if ($this->collection[$i]->isInRound() && ($this->collection[$i]->getAmount() > 0))
                return $this->collection[$i];

            $i ++;
        }while($i !== $currentPlayerPlace);

        return false;
    }

    public function hasOnlyAllInPlayers(): bool
    {
        $inRound = 0;
        $allIn = 0;

        foreach ($this->collection as $player){
            $inRound = $player->isInRound() ? $inRound + 1 :  $inRound;
            $allIn = $player->getLastActionId() === 3 ? $allIn + 1 : $allIn;
        }

        return $inRound === $allIn;
    }

    public function isOnlyOneActivePlayerInRound()
    {
        $inRound = 0;

        foreach ($this->collection as $player){
            $inRound = $player->isInRound() ? $inRound + 1 :  $inRound;
        }

        return $inRound === 1;
    }

    public function isNextPlayerLastRaise($currentPlace,$lastRaisePlace):bool
    {
        $current = false;
        $nextPlayer = null;

        foreach ($this->collection as $player){
            if ($current && $player->isInRound()){
                $nextPlayer = $player;
                break;
            }
            if ($player->getPlace() === $currentPlace){
                $current = true;
            }
        }

        return $nextPlayer->getPlace() === $lastRaisePlace;
    }

    /**
     * кто будет делать шоудаун сейчас?
     *
     * @param int $lastRaiseUserPlace
     * @return $this
     */
    public function showdownPlayerActions(int $lastRaiseUserPlace):self
    {
        $canBeChange = true;
        $i = 0;
        $showdownPlayerPlace = $this->getShowdownPlayerPlace();

        if ($lastRaiseUserPlace === -1) {
            $this->sortFromDealer();
        }else {
            if ($showdownPlayerPlace > -1)
                $this->sortFromPlace($showdownPlayerPlace);
            else
                $this->sortFromPlace($lastRaiseUserPlace - 1);
        }

        $this->each(function (Player $player) use (&$canBeChange,&$i){
            $player->setIsCurrentShowdown(false);

            if ($player->isInRound() && $canBeChange && $i > 0 && $player->getLastActionId() !== 3){
                $player->setIsCurrentShowdown(true);
                $canBeChange = false;
            }

            $i ++;
        });

        $this->sortByPlaces();

        return $this;
    }

    private function getShowdownPlayerPlace():int
    {
        foreach ($this->collection as $player){
            if ($player->isCurrentShowdown())
                return $player->getPlace();
        }

        return -1;
    }
}
