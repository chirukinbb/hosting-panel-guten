<?php

namespace App\Game;

use App\Game\Collections\ActionCollection;
use App\Game\Collections\BankCollection;
use App\Game\Collections\CardsCollection;

class Player
{
    protected int $userId;
    protected string $name;
    protected string $avatar;
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
        $this->addToBank(0);
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
        $this->bank->changeAmount($amount, '-');
    }

    public function getBank(): int
    {
        return $this->bank->getAll();
    }

    public function getAmount():int
    {
        return $this->bank->getAmount();
    }

    /**
     * @return int
     */
    public function getPlayerId(): int
    {
        return $this->playerId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $avatar
     */
    public function setAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    /**
     * @param bool $inGame
     */
    public function setInGame(bool $inGame): void
    {
        $this->inGame = $inGame;
    }

    /**
     * @return bool
     */
    public function isInGame(): bool
    {
        return $this->inGame;
    }

    /**
     * @param bool $inRound
     */
    public function setInRound(bool $inRound): void
    {
        $this->inRound = $inRound;
    }

    /**
     * @return bool
     */
    public function isInRound(): bool
    {
        return $this->inRound;
    }
}
