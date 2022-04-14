<?php

namespace App\Game\Traits;

use App\Game\Player;

trait PlayerTrait
{
    private int $currentIndex;

    public function changeStatuses()
    {
        $this->players->changeStatuses();
    }
}
