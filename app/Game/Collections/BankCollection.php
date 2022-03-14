<?php

namespace App\Game\Collections;

use App\Abstracts\AbstractGameCollection;

class BankCollection extends AbstractGameCollection
{
    protected int  $step;
    protected int  $amount;

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
}
