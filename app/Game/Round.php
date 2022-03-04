<?php

namespace App\Game;

class Round
{
    protected int $number;
    protected array $values;
    /**
     * @param Card[]
     */
    protected array $tableCards;
    protected array $combos;

    public function __construct(protected array $cardsPool)
    {
        $this->combos = config('poker.combos');
    }

    /**
     * @param int $number
     */
    public function setNumber(int $number): void
    {
        $this->number = $number;
    }

    public function changeDealer(array $players)
    {
        $index = array_search((object)['isDealer'=>true], $players);
        $players[$index]->setDealerStatus(false);
        $players[($index === (count($players) - 1)) ? ($index + 1) : 0]->setDealerStatus(true);
    }

    public function preFlop(array $players, int $cardsInHand)
    {
        /**
         * @var Player $player
         */
        foreach ($players as $player){
            for ($i = 0; $i < $cardsInHand; $i ++){
                $cardIndex = \Arr::random(array_keys($this->cardsPool));
                $player->giveCard($cardIndex, $this->cardsPool[$cardIndex]);
                unset($this->cardsPool[$cardIndex]);
            }
        }
    }

    public function flop()
    {
        $this->showTableCarts(3);
    }

    public function turn()
    {
        $this->showTableCarts(1);
    }

    public function river()
    {
        $this->showTableCarts(1);
    }

    public function handsValue(array $players)
    {
        /**
         * @var Player $player
         */
        foreach ($players as $player){
            $cards = array_merge(
                array_values($this->tableCards),
                $player->getCards()
            );
        }
    }

    protected function showTableCarts(int $count)
    {
        for ($i = 0; $i < $count; $i ++){
            $cardIndex = \Arr::random(array_keys($this->cardsPool));
            $this->tableCards[] = $this->cardsPool[$cardIndex];
            unset($this->cardsPool[$cardIndex]);
        }
    }
}
