<?php

namespace App\Game\Traits;

use App\Game\Card;

trait CombinedCarsTrait
{
    public function combinedByNominal(): array
    {
        $cards = [];
        /**
         * @var Card $card
         */
        foreach ($this->userCardsPool as $card){
            $nominalId = $card->getNominalIndex();
            $cards[$nominalId][] = $card;
        }

        return $cards;
    }

    public function combinedBySuits(): array
    {
        $cards = [];
        /**
         * @var Card $card
         */
        foreach ($this->userCardsPool as $card){
            $suitId = $card->getSuitIndex();
            $cards[$suitId][] = $card;
        }

        return $cards;
    }
}
