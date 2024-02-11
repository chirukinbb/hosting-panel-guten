<?php

namespace App\Game;

class RepeatCardsByNominal
{
    /**
     * @param Card[] $cards
     */
    public function __construct(protected int $nominalIndex, protected array $cards)
    {
    }

    /**
     * @return int
     */
    public function getNominalIndex(): int
    {
        return $this->nominalIndex;
    }

    /**
     * @return string
     */
    public function getNominalName(): string
    {
        return $this->getCard(1)->getNominalIndex();
    }

    /**
     * @return Card[]
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    public function getCard(int $index): Card
    {
        return $this->cards[$index];
    }

    public function repeatedCardCount(): int
    {
        return count($this->cards);
    }
}
