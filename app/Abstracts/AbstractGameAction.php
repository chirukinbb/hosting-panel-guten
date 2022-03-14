<?php

namespace App\Abstracts;

abstract class AbstractGameAction
{
    protected string $name;
    protected int $id;
    protected bool $isActive;

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
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    abstract public function handle();
}
