<?php

namespace App\Game;

class RepeatCardsBySuits
{
    /**
     * @param Card[] $cards
     */
    public function __construct(protected string $suit, protected array $cards)
    {
    }

    /**
     * @return string
     */
    public function getSuit(): string
    {
        return $this->suit;
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
