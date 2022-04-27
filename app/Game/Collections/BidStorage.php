<?php

namespace App\Game\Collections;

class BidStorage
{
    protected int $step;
    protected array $storage;

    public function add(int $bid,int $step): void
    {
        $this->storage[$step] = isset($this->storage[$step]) ? $this->storage[$step] + $bid : $bid;
    }

    public function all():int
    {
        return array_sum($this->storage);
    }

    public function annulled()
    {
        $this->storage = [];
    }
}
