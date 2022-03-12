<?php

namespace App\Sockets;

use \App\Game\Collections\PlayersCollection as Collection;

class PlayersCollection extends Collection
{
    protected int $type;

    /**
     * @param int $type
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function push(object $obj): Collection
    {
        $this->collection[$this->type] = $obj;

        return $this;
    }

    /**
     * @param int $index
     * @return Collection
     */
    public function get(int $index): object
    {
        return parent::get($index);
    }
}
