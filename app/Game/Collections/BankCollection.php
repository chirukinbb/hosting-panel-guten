<?php

namespace App\Game\Collections;

use App\Abstracts\AbstractGameCollection;

class BankCollection extends AbstractGameCollection
{
    protected int  $border;
    /**
     * @var BankStorage[]
     */
    protected array $collection;

    /**
     * @param int $border
     */
    public function setBorder(int $border): void
    {
        $this->border = $border;
    }

    public function add(int $amount)
    {
        if (isset($this->collection[$this->border])) {
            $this->collection[$this->border]->countIncrement();
            $this->collection[$this->border]->addAmount($amount);
        } else{
            $this->collection[$this->border] = new BankStorage($amount,1);
        }
    }

    public function getAll()
    {
        return array_sum($this->collection);
    }

    public function getPart(int $border)
    {
        return $this->collection[$border];
    }

    public function annulledAmount(int $border)
    {
        unset($this->collection[$border]);
    }

    public function getBankValueByAbsBorder(int $border): int
    {
        return $this->collection[$border]->getAmount();
    }

    /**
     * сливает велъю границ с одинаковым количеством игроков
     * поддержавших ее
     */
    public function normalize()
    {
        $collection = $this->collection;

        while (count($collection) > 1){
            $curBank = array_shift($collection);
            $nextBank = array_shift($collection);

            if ($curBank->getCount() === $nextBank->getCount()){
                $curBankIndex = array_search($curBank,$this->collection);
                $nextBankIndex = array_search($nextBank,$this->collection);

                unset($this->collection[$curBankIndex]);
                $nextBank->addAmount($curBank->getAmount());
                $this->collection[$nextBankIndex] = $nextBank;
            }

            $collection = array_merge($nextBank,$collection);
        }
    }
}
