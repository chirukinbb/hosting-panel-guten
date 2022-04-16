<?php

namespace App\Game;

use App\Game\Collections\BankCollection;
use App\Game\Collections\CardsCollection;
use App\Game\Collections\PlayersCollection;

class Round
{
    protected CardsCollection $tableCards;
    protected array $combos;
    protected BankCollection $bankCollection;
    protected int $lastRaisePlayerId;
    protected int $lastAuctionPlayerId;
    protected int $maxBidInTurn;
    protected int $currentStepInTurn;

    public function __construct(protected CardsCollection $cardsPool,protected int $number, protected int $ante)
    {
        $this->combos = config('poker.combos');
        $this->tableCards = new CardsCollection();
        $this->bankCollection = new BankCollection();
    }

    /**
     * @return int
     */
    public function getAnte(): int
    {
        return $this->ante;
    }

    /**
     * @param int $currentStepInTurn
     */
    public function setCurrentStepInTurn(int $currentStepInTurn): void
    {
        $this->currentStepInTurn = $currentStepInTurn;
    }

    /**
     * @return int
     */
    public function getCurrentStepInTurn(): int
    {
        return $this->currentStepInTurn;
    }

    /**
     * @return int
     */
    public function getMaxBidInTurn(): int
    {
        return $this->maxBidInTurn;
    }

    /**
     * @param int $maxBidInTurn
     */
    public function setMaxBidInTurn(int $maxBidInTurn): void
    {
        $this->maxBidInTurn = $maxBidInTurn;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    public function preFlop(Player $player, int $cardsInHand)
    {
        for ($i = 0; $i < $cardsInHand; $i ++){
            $cardIndex = \Arr::random($this->cardsPool->keys());
            $player->giveCard($this->cardsPool->get($cardIndex));
            $this->cardsPool->remove($cardIndex);
        }
    }

    public function putCardsOnTable(int $count)
    {
        for ($i = 0; $i < $count; $i ++){
            $cardIndex = \Arr::random($this->cardsPool->keys());
            $this->tableCards->push($this->cardsPool->get($cardIndex));
            $this->cardsPool->remove($cardIndex);
        }
    }

    public function checkHandValue(Player $player, int $cardsInHand): bool
    {
        for ($i = 1; $i < $cardsInHand;  $i ++) {
            for ($j = $i + 1; $j <= $cardsInHand; $j ++) {
                $playerHandCards = $player->getCards();
                $tableCards = clone $this->tableCards;
                $userCardsPool = $tableCards->push($playerHandCards->get($i - 1));
                $userCardsPool = $userCardsPool->push($playerHandCards->get($j - 1));
                $checker = new CombosChecker($userCardsPool);

                foreach ($this->combos as $index => $combo) {
                    if ($comboObj = $checker->check($combo)) {
                        $player->setCombo($comboObj)->setComboIndex($index);

                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function getPlayerWithStrongestHand(PlayersCollection $players): array
    {
        return $players->getWithStrongestHand();
    }

    /**
     * @param CardsCollection $tableCards
     */
    public function setTableCards(CardsCollection $tableCards): void
    {
        $this->tableCards = $tableCards;
    }

    public function getFullBank()
    {
        return $this->bankCollection->getAll();
    }

    public function getPartBank(int $step)
    {
        return $this->bankCollection->getPart($step);
    }

    public function payBlind(Player $player, int $amount)
    {
        $player->addToBank(0,$amount);
    }

    /**
     * @param int $lastRaisePlayerId
     */
    public function setLastRaiseUserId(int $lastRaisePlayerId): void
    {
        $this->lastRaisePlayerId = $lastRaisePlayerId;
    }

    /**
     * @return int
     */
    public function getLastRaiseUserId(): int
    {
        return $this->lastRaisePlayerId;
    }

    /**
     * @param int $lastAuctionPlayerId
     */
    public function setLastAuctionUserId(int $lastAuctionPlayerId): void
    {
        $this->lastAuctionPlayerId = $lastAuctionPlayerId;
    }

    /**
     * @return int
     */
    public function getLastAuctionUserId(): int
    {
        return $this->lastAuctionPlayerId;
    }

    public function eachCardOnTable(callable $func)
    {
        $this->tableCards->each($func);
    }

    /**
     * @param int $lastAuctionPlayerId
     */
    public function setLastAuctionPlayerId(int $lastAuctionPlayerId): void
    {
        $this->lastAuctionPlayerId = $lastAuctionPlayerId;
    }

    /**
     * @param int $lastRaisePlayerId
     */
    public function setLastRaisePlayerId(int $lastRaisePlayerId): void
    {
        $this->lastRaisePlayerId = $lastRaisePlayerId;
    }

    /**
     * @return int
     */
    public function getLastRaisePlayerId(): int
    {
        return $this->lastRaisePlayerId;
    }

    /**
     * @return int
     */
    public function getLastAuctionPlayerId(): int
    {
        return $this->lastAuctionPlayerId;
    }

    public function setBids(int $index, int $bid): void
    {
        $this->bankCollection->setStep($index);
        $this->bankCollection->add($bid);
    }

    public function getBids(int $index, int $bid): int|float
    {
        return $this->bankCollection->getAll();
    }

    public function annulledAmount(int $step)
    {
        $this->bankCollection->annulledAmount($step);
    }
}
