<?php

namespace App\Game\Traits;

trait PlayerTrait
{
    public function changeStatuses(int $currentDealerIndex)
    {
        $prevIndex = ($currentDealerIndex < 1) ? $this->players->count() - 1 : $currentDealerIndex - 1;
        $prevPlayer =  $this->players->get($prevIndex);
        $prevPlayer->setDealerStatus(false);

        $player = $this->players->get($currentDealerIndex);
        $player->setDealerStatus(true);
        $player->setLBStatus(false);

        $nextIndex = ($currentDealerIndex === ($this->players->count() - 1)) ? 0 : $currentDealerIndex + 1;
        $nextPlayer =  $this->players->get($nextIndex);
        $nextPlayer->setLBStatus(true);
        $nextPlayer->setBBStatus(false);

        $nextIndex2 = (($currentDealerIndex + 2) > $this->players->count()) ? ($currentDealerIndex - $this->players->count() + 2) : $currentDealerIndex + 2;
        $nextPlayer2 =  $this->players->get($nextIndex2);
        $nextPlayer2->setBBStatus(true);
    }
}
