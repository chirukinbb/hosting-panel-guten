<?php

namespace App\Game;

use App\Game\Collections\CardsCollection;
use App\Game\Collections\NominalCombinedCardsCollection;
use App\Game\Collections\RepeatCardsByNominalCollection;
use App\Game\Collections\RepeatCardsBySuitCollection;
use App\Game\Collections\SuitCombinedCardsCollection;
use App\Game\Traits\CombinedCarsTrait;
use App\Game\Traits\RepeatCardsTrait;

class CombosChecker
{
    use CombinedCarsTrait,RepeatCardsTrait;

    protected SuitCombinedCardsCollection $suitCombinedCardsCollection;
    protected NominalCombinedCardsCollection $nominalCombinedCardsCollection;
    protected RepeatCardsByNominalCollection $repeatedCardsByNominal;
    protected RepeatCardsBySuitCollection $repeatedCardsBySuits;

    public function __construct(protected CardsCollection $userCardsPool)
    {
        $this->suitCombinedCardsCollection = $this->getSuitCombinedCardsCollection();
        $this->nominalCombinedCardsCollection = $this->getNominalCombinedCardsCollection();
        $this->repeatedCardsBySuits = $this->getCardsBySuits();
        $this->repeatedCardsByNominal = $this->getCardsByNominal();
    }

    /**
     * @return NominalCombinedCardsCollection
     */
    public function getNominalCombinedCardsCollection(): NominalCombinedCardsCollection
    {
        return $this->combinedByNominal();
    }

    /**
     * @return SuitCombinedCardsCollection
     */
    public function getSuitCombinedCardsCollection(): SuitCombinedCardsCollection
    {
        return $this->combinedBySuits();
    }

    /**
     * @return RepeatCardsByNominalCollection
     */
    public function getCardsByNominal(): RepeatCardsByNominalCollection
    {
        return $this->getRepeatByNominal();
    }

    /**
     * @return RepeatCardsBySuitCollection
     */
    public function getCardsBySuits(): RepeatCardsBySuitCollection
    {
        return $this->getRepeatBySuits();
    }

    public function check(string $combo)
    {
        return method_exists($this,$combo) ? call_user_func([$this,$combo]) : false;
    }

    protected function highCard(array $excludedNominalIds = [],$isExcluded = true): string
    {
        $highCard  =  $this->userCardsPool->getHighCard($excludedNominalIds,$isExcluded);

        return sprintf('High Cart %s',$highCard->getNominalName());
    }

    protected function onePair(): bool|string
    {
        if ($this->repeatedCardsByNominal->count() === 1) {
            $key =  $this->repeatedCardsByNominal->keys()[0];
            $repeatedCards = $this->repeatedCardsByNominal->get($key);
            $card = $repeatedCards->get(0);

            return $repeatedCards->count() ===  2 ?
                sprintf(
                    'One Pair of %sth, %s',
                    $card->getNominalName(),
                    $this->highCard([$card->getNominalIndex()])
                )  : false;
        }

        return false;
    }

    protected function twoPairs(): bool|string
    {
        if ($this->repeatedCardsByNominal->count() > 1) {
            $keys = $this->repeatedCardsByNominal->keys();
            $firstCards = $this->repeatedCardsByNominal->get($keys[0]);
            $secondCards = $this->repeatedCardsByNominal->get($keys[1]);
            $firstCard = $firstCards->get(0);
            $secondCard =  $secondCards->get(0);

            return ($firstCards->count() === 2 && $secondCards->count() === 2) ?
                sprintf(
                    'Two Pairs of %sth & %sth, %s',
                    $firstCard->getNominalName(),
                    $secondCard->getNominalName(),
                    $this->highCard([$firstCard->getNominalIndex(),$secondCard->getNominalIndex()])
                )  : false;
        }

        return false;
    }

    protected function threeOfKind(): bool|string
    {
        if ($this->repeatedCardsByNominal->count() > 0) {
            $keys = $this->repeatedCardsByNominal->keys();
            $firstCards = $this->repeatedCardsByNominal->get($keys[0]);
            $firstCard = $firstCards->get(0);

            return ($firstCards->count() === 3) ?
                sprintf(
                    'Three of %sth, %s',
                    $firstCard->getNominalName(),
                    $this->highCard([$firstCard->getNominalIndex()])
                )  : false;
        }

        return false;
    }

    protected function fourOfKind(): bool|string
    {
        if ($this->repeatedCardsByNominal->count() > 0) {
            $keyOfFour = null;

            foreach ($this->repeatedCardsByNominal->keys() as $key) {
                if ($this->repeatedCardsByNominal->get($key)->count() === 4)
                    $keyOfFour = $key;
            }

            if (is_null($keyOfFour))
                return false;

            $firstCards = $this->repeatedCardsByNominal->get($keyOfFour);
            $firstCard = $firstCards->get(0);

            return ($firstCards->count() === 4) ?
                sprintf(
                    'Four of %sth, %s',
                    $firstCard->getNominalName(),
                    $this->highCard([$firstCard->getNominalIndex()])
                )  : false;
        }

        return false;
    }

    protected function straight()
    {
        if ($this->nominalCombinedCardsCollection->count() > 5){
            $nominalPool = $this->nominalCombinedCardsCollection->keys();
            $includedNominalIds = [];
            $i = 1;
            $prevNominalIndex = -5;

            foreach ($nominalPool as $nominal) {
                if ($nominal - $prevNominalIndex === 1){
                    $includedNominalIds[] = $nominal;
                    $i ++;
                    if ($i === 5){
                        return sprintf('Straight with %s',$this->highCard($includedNominalIds,false));
                    }
                }else{
                    $prevNominalIndex =  $nominal;
                    $includedNominalIds = [$nominal];
                    $i  = 1;
                }
            }
        }

        return false;
    }

    protected function flush(): bool|string
    {
        foreach ($this->repeatedCardsBySuits->keys() as $key) {
            $cards  = $this->repeatedCardsBySuits->get($key);

            if ($cards->count() > 4) {
                $includeNominalIndexes = [];

                foreach ($cards->keys() as $cardIndex) {
                    $card = $cards->get($cardIndex);
                    $includeNominalIndexes[] = $card->getNominalIndex();
                }

                return sprintf(
                    'Flush with %s, %s',
                    $card->getSuitName(),
                    $this->highCard($includeNominalIndexes,false)
                );
            }
        }

        return false;
    }

    protected function fullHouse(): bool|string
    {
        if ($this->repeatedCardsByNominal->count() > 1) {
            $keys = $this->repeatedCardsByNominal->keys();
            $keyOfPair = $keyOfThree = null;

            foreach ($keys as $key) {
                $keyOfPair = $this->repeatedCardsByNominal->get($key)->count() === 2 ? $key : $keyOfPair;
                $keyOfThree = $this->repeatedCardsByNominal->get($key)->count() === 3 ? $key : $keyOfThree;

                if (!is_null($keyOfPair) && !is_null($keyOfThree)) {

                    return sprintf(
                        'Full House of 3 %s`th & 2 %s`th',
                        $this->repeatedCardsByNominal->get($keyOfThree)->get(0)->getNominalName(),
                        $this->repeatedCardsByNominal->get($keyOfPair)->get(0)->getNominalName()
                    );
                }
            }
        }

        return  false;
    }

    protected function straightFlush(): bool|string
    {
        foreach ($this->repeatedCardsBySuits->keys() as $key) {
            $cards  = $this->repeatedCardsBySuits->get($key);

            if ($cards->count() > 4) {
                $cards  = $cards->sortByNominal();
                $includeNominalIndexes = [];
                $prevIndex = -5;
                $i = 1;

                foreach ($cards->keys() as $cardIndex) {
                    $card = $cards->get($cardIndex);
                    $cardIndex  = $card->getNominalIndex();

                    if ($cardIndex - $prevIndex === 1) {
                        $includeNominalIndexes[] = $cardIndex;
                        $prevIndex = $cardIndex;
                        $i++;

                        if ($i === 5) {
                            return sprintf('Straight Flush with %s',$this->highCard($includeNominalIndexes,false));
                        }
                    } else {
                        $prevIndex = $cardIndex;
                        $includeNominalIndexes = [$card->getNominalIndex()];
                        $i = 1;
                    }
                }
            }
        }

        return false;
    }

    protected function royalFlush()
    {}

    protected function fff()
    {}
}

