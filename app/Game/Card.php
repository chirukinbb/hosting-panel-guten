<?php

namespace App\Game;

class Card
{
    public function __construct(
        protected int $nominalIndex,
        protected int $suitIndex,
        protected int $id,
        protected string $nominalName,
        protected string $suitName
    ) {}

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
        return $this->nominalName;
    }

    /**
     * @return int
     */
    public function getSuitIndex(): int
    {
        return $this->suitIndex;
    }

    /**
     * @return string
     */
    public function getSuitName(): string
    {
        return $this->suitName;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->nominalName.' '.$this->suitName;
    }
}

