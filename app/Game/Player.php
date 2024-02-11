<?php

namespace App\Game;

use App\Game\Collections\ActionCollection;
use App\Game\Collections\BankCollection;
use App\Game\Collections\BidStorage;
use App\Game\Collections\CardsCollection;
use App\Game\Collections\UserComboCollection;

class Player
{
    protected string $name;
    protected string $avatar;
    protected CardsCollection $cards;
    protected int $place;
    protected UserComboCollection $combo;
    protected bool $inGame = true;
    protected bool $inRound = true;
    protected bool $isDealer = false;
    protected bool $isBB = false;
    protected bool $isLB = false;
    protected ActionCollection $actions;
    protected BidStorage $bid;
    protected int $amount;
    protected int $lastActionId = -1;
    // его ли ход на шоудауне
    protected  bool $isCurrentShowdown = false;
    // открыты ли карты
    protected  bool $isOpenCards = false;
    protected  bool $winner = false;
    // проходил ли шоудаун
    protected bool $isShowdownPass = true;
    protected ?int $placeInGame = null;

    public function __construct(protected int $userId)
    {
        $this->cards = new CardsCollection();
        $this->actions = new ActionCollection();
        $this->combo = new UserComboCollection();
        $this->amount = 1000;
        $this->bid = new BidStorage();
    }

    /**
     * @param bool $isShowdownPass
     */
    public function setIsShowdownPass(bool $isShowdownPass): void
    {
        $this->isShowdownPass = $isShowdownPass;
    }

    /**
     * @return bool
     */
    public function isShowdownPass(): bool
    {
        return $this->isShowdownPass;
    }

    /**
     * @return bool
     */
    public function isOpenCards(): bool
    {
        return $this->isOpenCards;
    }

    /**
     * @param bool $isOpenCards
     */
    public function setIsOpenCards(bool $isOpenCards): void
    {
        $this->isOpenCards = $isOpenCards;
    }

    /**
     * @param bool $isCurrentShowdown
     */
    public function setIsCurrentShowdown(bool $isCurrentShowdown): void
    {
        $this->isCurrentShowdown = $isCurrentShowdown;
    }

    /**
     * @param int $placeInGame
     */
    public function setPlaceInGame(int $placeInGame): void
    {
        $this->placeInGame = $placeInGame;
    }

    /**
     * @return int|null
     */
    public function getPlaceInGame(): ?int
    {
        return $this->placeInGame;
    }

    /**
     * @return bool
     */
    public function isCurrentShowdown(): bool
    {
        return $this->isCurrentShowdown;
    }

    /**
     * @param bool $winner
     */
    public function setWinner(bool $winner): void
    {
        $this->winner = $winner;
    }

    /**
     * @return bool
     */
    public function isWinner(): bool
    {
        return $this->winner;
    }

    /**
     * @param int $lastActionId
     */
    public function setLastActionId(int $lastActionId): void
    {
        $this->lastActionId = $lastActionId;
    }

    /**
     * @return int
     */
    public function getLastActionId(): int
    {
        return $this->lastActionId;
    }

    public function setPlaceOnDesc(int $place)
    {
        $this->place = $place;
    }

    public function removeCards()
    {
        $this->cards->empty();
    }

    public function removeCombos()
    {
        $this->combo->empty();
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
        $this->combo->push($combo);

        return $combo;
    }

    /**
     * @return UserCombo|null
     */
    public function getCombo(int $index): ?object
    {
        return $this->combo->get($index);
    }

    /**
     * @return bool
     */
    public function isDealer(): bool
    {
        return $this->isDealer;
    }

    public function addToBid(int $amount,int $step)
    {
        $this->amount -= $amount;
        $this->bid->add($amount, $step);
    }

    public function getBid(): int
    {
        return $this->bid->all();
    }

    public function getAmount():int
    {
        return $this->amount;
    }

    public function addAmount(int $amount)
    {
        $this->amount += $amount;
    }

    public function annulledBid()
    {
        $this->bid->annulled();
    }
    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
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

    public function eachCard(callable $func)
    {
        $this->cards->each($func);
    }

    public function eachAction(callable $func)
    {
        $this->actions->each($func);
    }
}
