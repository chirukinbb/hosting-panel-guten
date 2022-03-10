<?php

namespace App\Game;

use App\Game\Collections\CardsCollection;
use App\Game\Collections\PlayersCollection;

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

    public function checkHandValue(Player $player, int $cardsInHand)
    {
        for ($i = 1; $i < $cardsInHand;  $i ++) {
            for ($j = $i + 1; $j <= $cardsInHand; $j ++) {
                $playerHandCards = $player->getCards();
                $tableCards = clone $this->tableCards;
                $userCardsPool = $tableCards->push($playerHandCards->get($i - 1));
                $userCardsPool = $userCardsPool->push($playerHandCards->get($j - 1));
                $checker = new CombosChecker($userCardsPool);

                foreach ($this->combos as $index => $combo) {
                    if ($comboObj = $checker->check($combo)) {
                        $player->setCombo($comboObj)->setComboIndex($index);

                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function getPlayerWithStrongestHand(PlayersCollection $players): Player
    {
        return $players->getWithStrongestHand();
    }

    /**
     * @param CardsCollection $tableCards
     */
    public function setTableCards(CardsCollection $tableCards): void
    {
        $this->tableCards = $tableCards;
    }
}
