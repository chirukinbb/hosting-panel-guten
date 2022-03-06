<?php

namespace App\Game;

class UserCombo
{
    public function __construct(
        protected string $name,
        protected int $highComboIndex,
        protected int $highCardIndex
    ) {}

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
    public function getHighCardIndex(): int
    {
        return $this->highCardIndex;
    }

    /**
     * @return int
     */
    public function getHighComboIndex(): int
    {
        return $this->highComboIndex;
    }
}
