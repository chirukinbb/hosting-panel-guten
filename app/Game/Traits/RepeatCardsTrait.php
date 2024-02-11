<?php

namespace App\Game\Traits;

use App\Game\Card;
use App\Game\Collections\CardsCollection;
use App\Game\Collections\NominalCombinedCardsCollection;
use App\Game\Collections\RepeatCardsByNominalCollection;
use App\Game\Collections\RepeatCardsBySuitCollection;
use App\Game\Collections\SuitCombinedCardsCollection;
use App\Game\RepeatCardsByNominal;
use App\Game\RepeatCardsBySuits;

trait RepeatCardsTrait
{
    public function getRepeatByNominal(): RepeatCardsByNominalCollection
    {
        $repeat = new RepeatCardsByNominalCollection();

        $this->nominalCombinedCardsCollection->each(function (CardsCollection $cardsCollection) use ($repeat) {
            if ($cardsCollection->count() > 1) {
                $repeat->setNominalId($cardsCollection->get(1)->getNominalIndex());
                $repeat->push($cardsCollection);
            }
        });

        return $repeat;
    }

    public function getRepeatBySuits(): RepeatCardsBySuitCollection
    {
        $repeat = new RepeatCardsBySuitCollection();

        $this->suitCombinedCardsCollection->each(function (CardsCollection $cardsCollection) use ($repeat) {
            if ($cardsCollection->count() > 1) {
                $repeat->setSuitId($cardsCollection->get(1)->getSuitIndex());
                $repeat->push($cardsCollection);
            }
        });

        return $repeat;
    }
}
