<?php

namespace App\Game\Collections;

use App\Abstracts\AbstractGameCollection;

class BankCollection extends AbstractGameCollection
{
    protected int  $step;
    protected int  $amount = 0;

    /**
     * @param int $amount
     */
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    public function addAmount(int $amount)
    {
        $this->amount += $amount;
    }

    /**
     * @param int $step
     */
    public function setStep(int $step): void
    {
        $this->step = $step;
    }

    public function add(int $amount)
    {
        $this->collection[$this->step] = isset($this->collection[$this->step]) ?
            $this->collection[$this->step] + $amount : $amount;
    }

    public function getAll()
    {
        return array_sum($this->collection);
    }

    public function getPart(int $step)
    {
        return $this->collection[$step];
    }

    public function changeAmount(int $delta, $direction = '+')
    {
        $this->amount = ($direction === '+') ?
            $this->amount + $delta :
            $this->amount - $delta;
    }

    public function annulledAmount(int $step)
    {
        $this->collection[$this->step] = 0;
    }
}
