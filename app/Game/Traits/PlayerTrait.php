<?php

namespace App\Game\Traits;

use App\Game\Player;

trait PlayerTrait
{
    public function changeStatuses()
    {
        $place = $this->players->changeStatuses();
        $this->round->setLastAuctionPlayerPlace($place);
        $this->round->setLastRaisePlayerPlace($place);
    }

    public function fold()
    {
        $userId = \Auth::id();

        $this->players->each(function (Player $player) use ($userId) {
            if ($player->getUserId() === $userId) {
                $player->setInRound(false);
                $player->setLastActionId(0);
            }
        });
    }

    public function check()
    {
        $userId = \Auth::id();

        $this->players->each(function (Player $player) use ($userId) {
            if ($player->getUserId() === $userId && $player->getBid() === $this->round->getMaxBid()) {
                $player->setLastActionId(1);
            }
        });
    }

    public function raise(int $amount)
    {
        $userId = \Auth::id();

        $this->players->each(function (Player $player) use ($userId, $amount) {
            if ($player->getUserId() === $userId && $this->round->getMaxBid() < ($bid = $player->getBid() + $amount)) {
                $player->addToBid($amount, $this->round->getCurrentStep());
                $this->round->setLastRaisePlayerPlace($player->getPlace());
                $this->round->setMaxBid($bid);
                $player->setLastActionId(2);
            }
        });
    }

    public function default()
    {
        $userId = \Auth::id();

        $this->players->each(function (Player $player) use ($userId) {
            if ($userId === $player->getUserId()) {
                if ($player->getBid() === $this->round->getMaxBid())
                    $this->check();
                else
                    $this->fold();
            }
        });
    }

    public function call()
    {
        $userId = \Auth::id();

        $this->players->each(function (Player $player) use ($userId) {
            if ($player->getUserId() === $userId && ($this->round->getMaxBid() - $player->getBid() < $player->getAmount())) {
                $amount = $this->round->getMaxBid() - $player->getBid();
                $player->addToBid($amount, $this->round->getCurrentStep());
                $player->setLastActionId(4);
            }
        });
    }
}
