<?php

namespace App\Game\Traits;

use App\Game\Player;
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

    public function auction()
    {
    }

    protected function putCardsOnTable(int $count)
    {
        $this->round->putCardsOnTable($count);
    }
}