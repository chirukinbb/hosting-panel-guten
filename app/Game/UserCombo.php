<?php

namespace App\Game;

use App\Game\Collections\CardsCollection;

class UserCombo
{
    protected int $comboIndex;
    protected CardsCollection $cards;

    public function __construct(
        protected string $name,
        protected int    $highCardIndex,
        protected int    $comboMeterCardIndex =  0,
        protected int    $comboMeterSecondCardIndex =  0
    ) {
        $this->cards = new CardsCollection();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getComboMeterCardIndex(): int
    {
        return $this->comboMeterCardIndex;
    }

    /**
     * @return int
     */
    public function getHighCardIndex(): int
    {
        return $this->highCardIndex;
    }

    /**
     * @param int $comboIndex
     */
    public function setComboIndex(int $comboIndex): void
    {
        $this->comboIndex = $comboIndex;
    }

    /**
     * @return int
     */
    public function getComboIndex(): int
    {
        return $this->comboIndex;
    }

    /**
     * @param CardsCollection|Card $cards
     */
    public function pushCard(CardsCollection|Card $cards): void
    {
        if (is_a($cards, CardsCollection::class)) {
            $cards->each(function (Card $card) {
                $this->cards->push($card);
            });
        }else{
            $this->cards->push($cards);
        }
    }

    public function eachCard(callable $func)
    {
        $this->cards->each($func);
    }
}
