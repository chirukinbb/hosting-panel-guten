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
    protected int $lastRaisePlayerPlace;
    protected int $lastAuctionPlayerPlace;
    protected int $maxBid = 0;
    protected int $currentStep = 0;
    protected int $countPlayersStart;
    protected int $countPlayersEnd;
    protected PlayersCollection $lostPlayers;

    public function __construct(protected CardsCollection $cardsPool, protected int $number, protected int $ante)
    {
        $this->combos = config('poker.combos');
        $this->tableCards = new CardsCollection();
        $this->bankCollection = new BankCollection();
        $this->lostPlayers = new PlayersCollection();
    }

    /**
     * @param int $countPlayersStart
     */
    public function setCountPlayersStart(int $countPlayersStart): void
    {
        $this->countPlayersStart = $countPlayersStart;
    }

    /**
     * @param int $countPlayersEnd
     */
    public function setCountPlayersEnd(int $countPlayersEnd): void
    {
        $this->countPlayersEnd = $countPlayersEnd;
    }

    /**
     * @return int
     */
    public function getCountPlayersEnd(): int
    {
        return $this->countPlayersEnd;
    }

    /**
     * @return int
     */
    public function getCountPlayersStart(): int
    {
        return $this->countPlayersStart;
    }

    /**
     * @return int
     */
    public function getPlayerPlace(): string
    {
        if ($this->countPlayersStart - $this->countPlayersEnd > 1)
            return ($this->countPlayersEnd + 1) . '/' . $this->countPlayersStart;

        return $this->countPlayersStart;
    }

    /**
     * @param Player $player
     */
    public function addLostPlayer(Player $player): void
    {
        $this->lostPlayers->push($player);
    }

    public function eachLooser(callable $func)
    {
        $this->lostPlayers->each(function (Player $player) use ($func) {
            call_user_func($func, $player);
        });
    }

    /**
     * @return int
     */
    public function getAnte(): int
    {
        return $this->ante;
    }

    /**
     * @param int $currentStep
     */
    public function setCurrentStep(int $currentStep): void
    {
        $this->currentStep = $currentStep;
        $this->maxBid = 0;
    }

    /**
     * @return int
     */
    public function getCurrentStep(): int
    {
        return $this->currentStep;
    }

    /**
     * @return int
     */
    public function getMaxBid(): int
    {
        return $this->maxBid;
    }

    /**
     * @param int $maxBid
     */
    public function setMaxBid(int $maxBid): void
    {
        $this->maxBid = $maxBid;
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
        for ($i = 0; $i < $cardsInHand; $i++) {
            $cardIndex = \Arr::random($this->cardsPool->keys());
            $player->giveCard($this->cardsPool->get($cardIndex));
            $this->cardsPool->remove($cardIndex);
        }
    }

    public function putCardsOnTable(int $count)
    {
        for ($i = 0; $i < $count; $i++) {
            $cardIndex = \Arr::random($this->cardsPool->keys());
            $this->tableCards->push($this->cardsPool->get($cardIndex));
            $this->cardsPool->remove($cardIndex);
        }
    }

    public function checkHandValue(Player $player, int $cardsInHand): bool
    {
        for ($i = 1; $i < $cardsInHand; $i++) {
            for ($j = $i + 1; $j <= $cardsInHand; $j++) {
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

    public function getBankCollection()
    {
        return $this->bankCollection->getCollection();
    }

    public function getPartBank(int $step)
    {
        return $this->bankCollection->getPart($step);
    }

    public function payBlind(Player $player, int $amount)
    {
        $player->addToBid($amount, $this->getCurrentStep());
    }

    /**
     * @param int $lastRaisePlayerPlace
     */
    public function setLastRaisePlayerPlace(int $lastRaisePlayerPlace): void
    {
        $this->lastRaisePlayerPlace = $lastRaisePlayerPlace;
    }

    /**
     * @return int
     */
    public function getLastRaiseUserPlace(): int
    {
        return $this->lastRaisePlayerPlace;
    }

    /**
     * @param int $lastAuctionPlayerPlace
     */
    public function setLastAuctionPlayerPlace(int $lastAuctionPlayerPlace): void
    {
        $this->lastAuctionPlayerPlace = $lastAuctionPlayerPlace;
    }

    /**
     * @return int
     */
    public function getLastAuctionPlayerPlace(): int
    {
        return $this->lastAuctionPlayerPlace;
    }

    public function eachCardOnTable(callable $func)
    {
        $this->tableCards->each($func);
    }

    /**
     * @return int
     */
    public function getLastRaisePlayerPlace(): int
    {
        return $this->lastRaisePlayerPlace;
    }

    public function setBids(int $index, int $bid): void
    {
        $this->bankCollection->setBorder($index);
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

    public function getBankValueByAbsBorder(int $border): int
    {
        return $this->bankCollection->getBankValueByAbsBorder($border);
    }

    public function normalizeBank()
    {
        $this->bankCollection->normalize();
    }
}
