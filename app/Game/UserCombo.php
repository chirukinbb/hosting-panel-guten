<?php

namespace App\Game;

class UserCombo
{
    protected int $comboIndex;

    public function __construct(
        protected string $name,
        protected int    $highCardIndex,
        protected int    $comboMeterCardIndex =  0,
        protected int    $comboMeterSecondCardIndex =  0
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
}
