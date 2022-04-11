<?php

namespace App\Game\Traits;

use App\Game\Player;

trait PlayerTrait
{
    private int $currentIndex;

    public function changeStatuses(int $currentDealerIndex)
    {
        $this->players->sortFromLB();

        $this->eachPlayer(function (Player $player) use ($currentDealerIndex) {
            $player->setDealerStatus(false);
            $player->setLBStatus(false);
            $player->setBBStatus(false);

            if ($player->getPlace() === $currentDealerIndex)
                $player->setDealerStatus(true);

            if (($player->getPlace() === $currentDealerIndex + 1) && $player->isInRound()) {
                $player->setLBStatus(true);
                $currentDealerIndex = ($currentDealerIndex + 1) % $this->playersCount;
            }else{
                $currentDealerIndex = ($currentDealerIndex + 1) % $this->playersCount;
            }

            if (($player->getPlace() === $currentDealerIndex + 1) && $player->isInRound()){
                $player->setBBStatus(true);
                $this->round->setLastRaiseUserId($player->getUserId());
                $this->round->setLastRaiseUserId($player->getUserId());
                $currentDealerIndex = ($currentDealerIndex + 1) % $this->playersCount;
            }else{
                $currentDealerIndex = ($currentDealerIndex + 1) % $this->playersCount;
            }

            $this->currentIndex = $currentDealerIndex;
        });

        $this->players->sortByPlaces();
    }
}
