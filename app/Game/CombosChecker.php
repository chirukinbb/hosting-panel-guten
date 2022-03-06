<?php

namespace App\Game;

use App\Game\Traits\CombinedCarsTrait;
use App\Game\Traits\RepeatCardsTrait;
use JetBrains\PhpStorm\Pure;

class CombosChecker
{
    use CombinedCarsTrait,RepeatCardsTrait;

    /**
     * @param Card[] $userCardsPool
     * @param RepeatCardsByNominal[]
     */
    protected array $cardsByNominal;
    /**
     * @param RepeatCardsBySuits[]
     */
    protected array $cardsBySuits;

    public function __construct(protected array $userCardsPool)
    {
        $this->cardsBySuits = $this->combinedBySuits();
        $this->cardsByNominal = $this->getCardsByNominal();
    }

    /**
     * @return array
     */
    public function getCardsByNominal(): array
    {
        return $this->getRepeatByNominals(
            $this->combinedByNominal()
        );
    }

    /**
     * @return array
     */
    public function getCardsBySuits(): array
    {
        return $this->getRepeatBySuits(
            $this->combinedBySuits()
        );
    }

    public function check()
    {}

    protected function highCard(array $excluded = []): string
    {
        $highCardNominalId = -1;
        $highCardName = null;

        /**
         * @var Card $card
         */
        foreach ($this->userCardsPool as $card){
            $cardNominalId = $card->getNominalIndex();

            if ($cardNominalId > $highCardNominalId && !in_array($cardNominalId,$excluded)){
                $highCardName = $card->getName();
                $highCardNominalId = $cardNominalId;
            }
        }

        return sprintf('High Cart %s',$highCardName);
    }

    protected function onePair(): bool|string
    {
        if (count($this->cardsByNominal) === 1) {
            /**
             * @var RepeatCardsByNominal $cards
             */
            $cards = $this->cardsByNominal[0];

            return $cards->repeatedCardCount() ===  2 ?
                sprintf(
                    'One Pair of %sth, %s',
                    $cards->getCard(1)->getNominalName(),
                    $this->highCard([$cards->getNominalIndex()])
                )  : false;
            }

        return false;
    }

    protected function twoPairs(): bool|string
    {
        if (count($this->cardsByNominal) === 2) {
            /**
             * @var RepeatCardsByNominal $firstCards
             * @var RepeatCardsByNominal $secondCards
             */
            $firstCards = $this->cardsByNominal[0];
            $secondCards = $this->cardsByNominal[2];

            return ($firstCards->repeatedCardCount() === 2 && $secondCards->repeatedCardCount() === 2) ?
                sprintf(
                    'Two Pairs of %sth & %sth, %s',
                    $firstCards->getCard(1)->getNominalName(),
                    $secondCards->getCard(1)->getNominalName(),
                    $this->highCard([$firstCards->getNominalIndex(),$secondCards->getNominalIndex()])
                )  : false;
        }

        return false;
    }

    protected function threeOfKind(): bool|string
    {
        if (count($this->cardsByNominal) === 1) {
            /**
             * @var RepeatCardsByNominal $cards
             */
            $cards = $this->cardsByNominal[0];

            return $cards->repeatedCardCount() ===  3 ?
                sprintf(
                    'Three of a kind of %s, %s',
                    $cards->getCard(1)->getNominalName(),
                    $this->highCard([$cards->getNominalIndex()])
                )  : false;
        }

        return false;
    }

    protected function fourOfKind(): bool|string
    {
        if (count($this->cardsByNominal) === 1) {
            /**
             * @var RepeatCardsByNominal $cards
             */
            $cards = $this->cardsByNominal[0];

            return $cards->repeatedCardCount() ===  4 ?
                sprintf(
                    'Three of a kind of %s, %s',
                    $cards->getCard(1)->getNominalName(),
                    $this->highCard([$cards->getNominalIndex()])
                )  : false;
        }

        return false;
    }


}

