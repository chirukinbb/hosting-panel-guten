<?php

namespace App\Game\Tests\Winner;

use App\Game\Card;
use App\Game\Collections\CardsCollection;
use App\Game\Collections\PlayersCollection;
use App\Game\Player;
use App\Game\Round;

abstract class AbstractTwoPlayers
{
    public function __construct()
    {
        $players = new PlayersCollection();
        $players->push($this->firstPlayer());
        $players->push($this->secondPlayer());

        $round = new  Round(1,$this->cardsOnTable());
        $round->setTableCards($this->cardsOnTable());
        $round->checkHandValue($players->get(1),2);
        $round->checkHandValue($players->get(0),2);

        echo 'Игрок 1 победил: '.(int)($players->getWithStrongestHand() === [0]);
    }

    protected function firstPlayer()
    {
        $player = new Player(5);
        $player->giveCard($this->firstCard());
        $player->giveCard($this->secondCard());
        $player->setPlaceOnDesc(0);

        return $player;
    }

    protected function secondPlayer()
    {
        $player = new Player(25);
        $player->giveCard($this->thirdCard());
        $player->giveCard($this->fourCard());
        $player->setPlaceOnDesc(1);

        return $player;
    }

    abstract protected function firstCard(): Card;
    abstract protected function secondCard(): Card;
    abstract protected function thirdCard(): Card;
    abstract protected function fourCard(): Card;

    abstract protected function cardsOnTable(): CardsCollection;
}
