<?php

namespace App\Game\Collections;

class BankStorage
{
    public function __construct(protected int $amount,protected int $count = 0 )
    {
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    public function countIncrement()
    {
        $this->count ++;
    }

    public function addAmount(int $amount)
    {
        $this->amount += $amount;
    }
}
