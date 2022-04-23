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

        $this->players->each(function (Player $player) use ($userId){
            if ($player->getUserId() === $userId) {
                $player->setInGame(false);
                $player->setLastActionId(0);
            }
        });
    }

    public function check()
    {
        $userId = \Auth::id();

        $this->players->each(function (Player $player) use ($userId){
            if ($player->getUserId() === $userId) {
                $player->setInGame(false);
                $player->setLastActionId(1);
            }
        });
    }

    public function raise(int $amount)
    {
        $userId = \Auth::id();

        $this->players->each(function (Player $player) use ($userId,$amount){
            if ($player->getUserId() === $userId) {
                $player->addToBid($amount);

                if ($this->round->getMaxBid() < ($bid = $player->getBid())) {
                    $this->round->setLastRaisePlayerPlace($userId);
                    $this->round->setMaxBid($bid);
                }

                $player->setLastActionId(2);
            }
        });
    }

    public function allIn()
    {
        $userId = \Auth::id();

        $this->players->each(function (Player $player) use ($userId){
            if ($player->getUserId() === $userId) {
                $amount  = $player->getAmount();
                $player->addToBid($amount);

                if ($this->round->getMaxBid() < ($bid = $player->getBid())) {
                    $this->round->setMaxBid($bid);
                    $this->round->setLastRaisePlayerPlace($userId);
                }

                $player->setLastActionId(3);
            }
        });
    }

    public function call()
    {
        $userId = \Auth::id();

        $this->players->each(function (Player $player) use ($userId){
            if ($player->getUserId() === $userId) {
                $amount = $this->round->getMaxBid() - $player->getBid();
                $player->addToBid($amount);
                $player->setLastActionId(4);
            }
        });
    }
}
