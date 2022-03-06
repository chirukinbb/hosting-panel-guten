<?php

namespace App\Game;

use App\Game\Collections\CardsCollection;

class Round
{
    protected CardsCollection $tableCards;
    protected array $combos;

    public function __construct(protected int $number, protected CardsCollection $cardsPool)
    {
        $this->combos = config('poker.combos');
        $this->tableCards = new CardsCollection();
    }

    public function preFlop(Player $player, int $cardsInHand)
    {
        for ($i = 0; $i < $cardsInHand; $i ++){
            $cardIndex = \Arr::random($this->cardsPool->keys());
            $player->giveCard($this->cardsPool->get($cardIndex));
            $this->cardsPool->remove($cardIndex);
        }
    }

    public function putCardsOnTable(int $count)
    {
        for ($i = 0; $i < $count; $i ++){
            $cardIndex = \Arr::random($this->cardsPool->keys());
            $this->tableCards->push($this->cardsPool->get($cardIndex));
            $this->cardsPool->remove($cardIndex);
        }
    }
}
