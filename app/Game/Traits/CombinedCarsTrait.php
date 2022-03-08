<?php

namespace App\Game\Traits;

use App\Game\Card;
use App\Game\Collections\CardsCollection;
use App\Game\Collections\NominalCombinedCardsCollection;
use App\Game\Collections\SuitCombinedCardsCollection;

trait CombinedCarsTrait
{
    public function combinedByNominal(): NominalCombinedCardsCollection
    {
        $cards = new NominalCombinedCardsCollection();

        $this->userCardsPool->each(function (Card $card) use ($cards){
            $cards->setNominalId($card->getNominalIndex());
            $cards->push($card);
        });

        return $cards->sortByNominalId();
    }

    public function combinedBySuits(): SuitCombinedCardsCollection
    {
        $cards = new SuitCombinedCardsCollection();

        $this->userCardsPool->each(function (Card $card) use ($cards){
            $cards->setSuitId($card->getSuitIndex());
            $cards->push($card);
        });

        return $cards;
    }
}
