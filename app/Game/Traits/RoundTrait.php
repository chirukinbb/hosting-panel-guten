<?php

namespace App\Game\Traits;

use App\Game\Player;
use App\Game\Round;

trait RoundTrait
{
    public function startRound()
    {
        $number = isset($this->round) ? $this->round->getNumber() : 0;
        $ante = intdiv($number,5) * 0.2 * $this->blind;
        $this->round  = new Round(clone $this->cardDeck,$number + 1,$ante);
        $this->round->setCountPlayersStart($this->getActivePlayersCount());
    }

    public function preFlop()
    {
        $this->players->each(function (Player $player) {
            $this->round->preFlop($player,$this->cardsInHand);
        });
    }

    public function calculateHandValues()
    {
         $this->players->each(function (Player $player){
             $this->round->checkHandValue($player, $this->cardsInHand);
         });
    }

    public function eachCardOnTable(callable $func)
    {
        $this->round->eachCardOnTable($func);
    }

    public function flop()
    {
        $this->putCardsOnTable(3);
    }

    public function turn()
    {
        $this->putCardsOnTable(1);
    }

    public function river()
    {
        $this->putCardsOnTable(1);
    }

    public function getRoundNumber()
    {
        return !empty($this->round) ? $this->round->getNumber() : 0;
    }

    public function getCurrentAnte()
    {
        return $this->round->getAnte();
    }

    protected function putCardsOnTable(int $count)
    {
        $this->round->putCardsOnTable($count);
    }

    public function getDealerPlace()
    {
        return $this->players->getDealerIndex();
    }

    public function setCurrentStepInRound(int $step)
    {
        $this->round->setCurrentStep($step);
    }

    public function getCurrentStepInRound()
    {
        return $this->round->getCurrentStep();
    }
}
