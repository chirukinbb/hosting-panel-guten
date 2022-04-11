<?php

namespace App\Game\Traits;

use App\Game\Player;
use App\Game\Round;

trait RoundTrait
{
    public function startRound(int $number)
    {
        $this->round  = new Round(clone $this->cardDeck,$number);
    }

    public function preFlop()
    {
        $this->players->each(function (Player $player) {
            $this->round->preFlop($player,$this->cardsInHand);
        });
    }

    public function calculateHandValues()
    {
         $this->players->each(function (Player $player) {
             $this->round->checkHandValue($player, $this->cardsInHand);
         });
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

    protected function putCardsOnTable(int $count)
    {
        $this->round->putCardsOnTable($count);
    }

    public function getDealerPlace()
    {
        return $this->players->getDealerIndex();
    }

    public function getAuctionUserId()
    {
        return $this->round->getActionTurnOfUserId();
    }
}
