<?php

namespace App\Game;

use App\Game\Collections\ActionCollection;
use App\Game\Collections\BankCollection;
use App\Game\Collections\CardsCollection;

class Player
{
    protected CardsCollection $cards;
    protected int $place;
    protected UserCombo $combo;
    protected bool $inGame = true;
    protected bool $inRound = true;
    protected bool $isDealer = false;
    protected bool $isBB = false;
    protected bool $isLB = false;
    protected ActionCollection $actions;
    protected BankCollection $bank;

    public function __construct(protected int $playerId)
    {
        $this->cards = new CardsCollection();
        $this->bank = new BankCollection();
        $this->bank->setAmount(1000);
    }

    public function setPlaceOnDesc(int $place)
    {
        $this->place = $place;
    }

    public function giveCard(Card $card)
    {
        $this->cards->push($card);
    }

    public function setDealerStatus(bool $isDealer)
    {
        $this->isDealer =  $isDealer;
    }

    public function setBBStatus(bool $isBB)
    {
        $this->isBB =  $isBB;
    }

    /**
     * @return bool
     */
    public function isBB(): bool
    {
        return $this->isBB;
    }

    /**
     * @return bool
     */
    public function isLB(): bool
    {
        return $this->isLB;
    }

    public function setLBStatus(bool $isLB)
    {
        $this->isLB =  $isLB;
    }

    public function getCards(): CardsCollection
    {
        return $this->cards;
    }

    public function getPlace(): int
    {
        return $this->place;
    }

    /**
     * @param UserCombo $combo
     */
    public function setCombo(UserCombo $combo): UserCombo
    {
        $this->combo = $combo;

        return $this->combo;
    }

    /**
     * @return UserCombo
     */
    public function getCombo(): UserCombo
    {
        return $this->combo;
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

    /**
     * @return bool
     */
    public function isDealer(): bool
    {
        return $this->isDealer;
    }

    public function addToBank(int $step, int $amount)
    {
        $this->bank->setStep($step);
        $this->bank->add($amount);
    }
}
