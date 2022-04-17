<?php

namespace App\Game\Collections;

use App\Abstracts\AbstractGameCollection;

class BankCollection extends AbstractGameCollection
{
    protected int  $border;

    /**
     * @param int $border
     */
    public function setBorder(int $border): void
    {
        $this->border = $border;
    }

    public function add(int $amount)
    {
        $this->collection[$this->border] = isset($this->collection[$this->border]) ?
            $this->collection[$this->border] + $amount : $amount;
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
        $this->collection[$border] = 0;
    }

    public function getBankValueByAbsBorder(int $border): int
    {
        return $this->collection[$border];
    }
}
