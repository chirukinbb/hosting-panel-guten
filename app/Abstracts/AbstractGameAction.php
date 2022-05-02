<?php

namespace App\Abstracts;

abstract class AbstractGameAction
{
    protected string $name;
    protected int $id;
    protected bool $isActive = false;
    protected bool $amountInMessage;
    protected int $amount;

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

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $payToBank
     */
    public function setPayToBank(bool $payToBank): void
    {
        $this->payToBank = $payToBank;
    }

    /**
     * @return bool
     */
    public function isPayToBank(): bool
    {
        return $this->payToBank;
    }

    /**
     * @param int $amount
     */
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    public function message($getCurrentBid): string
    {
        return $this->name.($this->amountInMessage ? $getCurrentBid : '');
    }
}
