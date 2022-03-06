<?php

namespace App\Game\Traits;

use App\Game\Round;

trait RoundTrait
{
    protected Round $round;

    public function startRound(int $number)
    {
        $this->round  = new Round($number,$this->cardDeck);
    }

    public function preFlop()
    {
        $this->players->each(function ($player) {
            $this->round->preFlop($player,$this->cardsInHand);
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

    public function handsValue(array $players)
    {
    }

    protected function putCardsOnTable(int $count)
    {
        $this->round->putCardsOnTable($count);
    }
}
