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

    protected function highCard(array $excludedNominalIds = [],$isExcluded = true): UserCombo|Card|bool
    {
        $highCard  =  $this->userCardsPool->getHighCard($excludedNominalIds,$isExcluded);

        if (empty($excludedNominalIds)) {
            $result = new UserCombo(sprintf('High Cart %s', $highCard->getNominalName()), $highCard->getNominalIndex());
            $result->pushCard($highCard);
        } else {
            $result = $highCard;
        }

        return $result;
    }

    protected function lowCard(array $excludedNominalIds = [],$isExcluded = true): UserCombo|Card
    {
        return $this->userCardsPool->getLowCard($excludedNominalIds,$isExcluded);
    }

    protected function onePair(): bool|UserCombo
    {
        if ($this->repeatedCardsByNominal->count() === 1) {
            $key =  $this->repeatedCardsByNominal->keys()[0];
            $repeatedCards = $this->repeatedCardsByNominal->get($key);
            $card = $repeatedCards->get(0);

            if ($repeatedCards->count() ===  2){
                $combo = new UserCombo(
                    sprintf(
                        'One Pair of %s`th%s',
                        $card->getNominalName(),
                        ($highCard  = $this->highCard([$card->getNominalIndex()])) ?
                            ', kicker '.$highCard->getNominalName() : ''
                    ), !$highCard ? $card->getNominalIndex() : 0,
                    !$highCard ? $highCard->getNominalIndex() : 0
                );

                $combo->pushCard($repeatedCards);
                $combo->pushCard($highCard);

                return $combo;
            }
        }

        return false;
    }

    protected function twoPairs(): bool|UserCombo
    {
        if ($this->repeatedCardsByNominal->count() > 1) {
            $keys = $this->repeatedCardsByNominal->keys();
            $pairsKeys = [];

            foreach ($keys as $key) {
                if ($this->repeatedCardsByNominal->get($key)->count() === 2)
                    $pairsKeys[]  = $key;
            }

            if (count($keys) < 2)
                return false;

            if (count($keys) === 3)
                $keys = array_slice($keys,-2,2);

            $firstCards = $this->repeatedCardsByNominal->get($keys[0]);
            $secondCards = $this->repeatedCardsByNominal->get($keys[1]);
            $firstCard = $firstCards->get(0);
            $secondCard =  $secondCards->get(0);

            if ($firstCards->count() === 2 && $secondCards->count() === 2){
                $combo = new UserCombo(
                    sprintf(
                        'Two Pairs of %s`th & %s`th, kicker %s',
                        $firstCard->getNominalName(),
                        $secondCard->getNominalName(),
                        ($highCard  = $this->highCard([$firstCard->getNominalIndex(),$secondCard->getNominalIndex()]))->getNominalName()
                    ),$secondCard->getNominalIndex(),$firstCard->getNominalIndex(),$highCard->getNominalIndex()
                );

                $combo->pushCard($firstCards);
                $combo->pushCard($secondCards);
                $combo->pushCard($highCard);

                return $combo;
            }
        }

        return false;
    }

    protected function threeOfKind(): bool|UserCombo
    {
        if ($this->repeatedCardsByNominal->count() > 0) {
            $keys = $this->repeatedCardsByNominal->keys();
            $threeKey = null;

            foreach ($keys as $key){
                if ($this->repeatedCardsByNominal->get($key)->count() === 3 && $key > $threeKey)
                    $threeKey = $key;
            }

            if (is_null($threeKey))
                return false;

            $firstCards = $this->repeatedCardsByNominal->get($threeKey);
            $firstCard = $firstCards->get(0);

            if ($firstCards->count() === 3) {
                $combo  = new UserCombo(
                    sprintf(
                        'Three of %s`th, kicker %s',
                        $firstCard->getNominalName(),
                        ($highCard  = $this->highCard([$firstCard->getNominalIndex()]))->getNominalName()
                    ),$firstCard->getNominalIndex(),$highCard->getNominalIndex()
                );

                $combo->pushCard($firstCards);
                $combo->pushCard($highCard);

                return $combo;
            }
        }

        return false;
    }

    protected function fourOfKind(): bool|UserCombo
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

            if ($firstCards->count() === 4) {
                $combo = new UserCombo(
                    sprintf(
                        'Four of %s`th, kicker %s',
                        $firstCard->getNominalName(),
                        ($highCard  = $this->highCard([$firstCard->getNominalIndex()]))->getNominalName()
                    ),$firstCard->getNominalIndex(),$highCard->getNominalIndex()
                );

                $combo->pushCard($firstCards);
                $combo->pushCard($highCard);

                return $combo;
            }
        }

        return false;
    }

    protected function straight(): bool|UserCombo
    {
        if ($this->nominalCombinedCardsCollection->count() > 4){
            $nominalPool = $this->nominalCombinedCardsCollection->keys();
            $includedNominalIds = [];
            $i = 1;
            $prevNominalIndex = -5;
            $comboCards = new CardsCollection();

            if (in_array(13,$nominalPool))
                array_unshift($nominalPool,1);

            foreach ($nominalPool as $nominal) {
                if ($nominal - $prevNominalIndex === 1){
                    $comboCards->push($this->nominalCombinedCardsCollection->get($nominal));
                    $includedNominalIds[] = $nominal;
                    $prevNominalIndex = $nominal;
                    $i ++;

                    if ($i === 5){
                        $combo = new UserCombo(
                            sprintf(
                                'Straight from %s to %s',
                                $this->lowCard($includedNominalIds,false)->getNominalName(),
                                ($highCard = $this->highCard($includedNominalIds,false))->getNominalName(),
                            ),
                            $highCard->getNominalIndex()
                        );
                        $combo->pushCard($comboCards);

                        return $combo;
                    }
                }else{
                    $comboCards->empty();
                    $prevNominalIndex =  $nominal;
                    $includedNominalIds = [$nominal];
                    $i  = 1;
                }
            }
        }

        return false;
    }

    protected function flush(): bool|UserCombo
    {
        foreach ($this->repeatedCardsBySuits->keys() as $key) {
            $cards  = $this->repeatedCardsBySuits->get($key);

            if ($cards->count() > 4) {
                $includeNominalIndexes = [];
                $comboCards = new CardsCollection();

                foreach ($cards->keys() as $cardIndex) {
                    $card = $cards->get($cardIndex);
                    $comboCards->push($card);
                    $includeNominalIndexes[] = $card->getNominalIndex();
                }

                $combo = new UserCombo(
                        sprintf(
                            'Flush with %s, high cart %s',
                            $card->getSuitName(),
                            ($highCard = $this->highCard($includeNominalIndexes,false))->getNominalName()
                        ),
                        $highCard->getNominalIndex()
                    );

                $combo->pushCard($comboCards);
                $combo->pushCard($highCard);

                return $combo;
            }
        }

        return false;
    }

    protected function fullHouse(): bool|UserCombo
    {
        if ($this->repeatedCardsByNominal->count() > 1) {
            $keys = $this->repeatedCardsByNominal->keys();
            $keyOfPair = $keyOfThree = null;

            foreach ($keys as $key) {
                $keyOfPair = $this->repeatedCardsByNominal->get($key)->count() === 2 ? $key : $keyOfPair;
                $keyOfThree = $this->repeatedCardsByNominal->get($key)->count() === 3 ? $key : $keyOfThree;

                if (!is_null($keyOfPair) && !is_null($keyOfThree)) {
                    $combo = new UserCombo(
                            sprintf(
                                'Full House of 3 %s`th & 2 %s`th',
                                ($threeCard = $this->repeatedCardsByNominal->get($keyOfThree)->get(0))->getNominalName(),
                                ($pair = $this->repeatedCardsByNominal->get($keyOfPair)->get(0))->getNominalName()
                            ),
                            $threeCard->getNominalIndex(),
                            $pair->getNominalIndex()
                        );

                    $combo->pushCard($threeCard);
                    $combo->pushCard($pair);

                    return $combo;
                }
            }
        }

        return  false;
    }

    protected function straightFlush(): bool|UserCombo
    {
        foreach ($this->repeatedCardsBySuits->keys() as $key) {
            $cards  = $this->repeatedCardsBySuits->get($key);

            if ($cards->count() > 4) {
                $cards  = $cards->sortByNominal();
                $includeNominalIndexes = [];
                $prevIndex = -5;
                $i = 1;
                $nominalPool = $cards->keys();
                $comboCards = new CardsCollection();

                if (in_array(13,$nominalPool))
                    array_unshift($nominalPool,1);

                foreach ($nominalPool as $cardIndex) {
                    $card = $cards->get($cardIndex);
                    $comboCards->push($card);
                    $cardIndex  = $card->getNominalIndex();

                    if ($cardIndex - $prevIndex === 1) {
                        $includeNominalIndexes[] = $cardIndex;
                        $prevIndex = $cardIndex;
                        $i++;

                        if ($i === 5) {
                            $format = (($highCard = $this->highCard($includeNominalIndexes,false))->getNominalIndex() === 13) ?
                                            'Royal Flush from %s to  %s' :
                                            'Straight Flush from %s to  %s';
                            $combo = new UserCombo(
                                    sprintf(
                                        $format,
                                        $this->lowCard($includeNominalIndexes,false)->getNominalName(),
                                        $highCard->getNominalName()
                                    ),
                                    $highCard->getNominalIndex()  === 13 ? 1 : 0,
                                    $highCard->getNominalIndex()
                                );

                            $combo->pushCard($comboCards);

                            return $combo;
                        }
                    } else {
                        $comboCards->empty();
                        $prevIndex = $cardIndex;
                        $includeNominalIndexes = [$card->getNominalIndex()];
                        $i = 1;
                    }
                }
            }
        }

        return false;
    }
}

