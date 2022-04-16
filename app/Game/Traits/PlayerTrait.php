<?php

namespace App\Game\Traits;

use App\Game\Player;

trait PlayerTrait
{
    public function changeStatuses()
    {
        $this->players->changeStatuses();
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
                $player->addToBank($this->round->getCurrentStepInTurn(), $amount);

                if ($this->round->getMaxBidInTurn() < ($bid = $player->getBank())) {
                    $this->round->setLastRaisePlayerId($userId);
                    $this->round->setMaxBidInTurn($bid);
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
                $player->addToBank($this->round->getCurrentStepInTurn(), $amount);

                if ($this->round->getMaxBidInTurn() < ($bid = $player->getBank())) {
                    $this->round->setMaxBidInTurn($bid);
                    $this->round->setLastRaiseUserId($userId);
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
                $amount = $this->round->getMaxBidInTurn() - $player->getBank();
                $player->addToBank($this->round->getCurrentStepInTurn(), $amount);
                $player->setLastActionId(4);
            }
        });
    }
}
