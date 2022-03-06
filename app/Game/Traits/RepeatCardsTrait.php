<?php

namespace App\Game\Traits;

use App\Game\Card;
use App\Game\RepeatCardsByNominal;
use App\Game\RepeatCardsBySuits;

trait RepeatCardsTrait
{
    public function getRepeatByNominals($cardsByNominal)
    {
        $repeat = [];

        /**
         * @var Card $card
         */
        foreach ($cardsByNominal as $nominalId => $cards) {
            if (count($cards) > 1){
                $repeat[] = new RepeatCardsByNominal($nominalId,$cards);
            }
        }

        /**
         * @param RepeatCardsByNominal
         */
        return $repeat;
    }

    public function getRepeatBySuits($cardsBySuits)
    {
        $repeat = [];

        /**
         * @var Card $card
         */
        foreach ($cardsBySuits as $suitId => $cards) {
            if (count($cards) > 1){
                $repeat[] = new RepeatCardsBySuits($suitId,$cards);
            }
        }

        /**
         * @param RepeatCardsBySuits
         */
        return $repeat;
    }
}
