<?php

namespace App\Game\Collections;

use App\Abstracts\AbstractGameCollection;
use App\Game\RepeatCardsByNominal;

class RepeatCardsByNominalCollection extends AbstractGameCollection
{
    /**
     * @param int $index
     * @return RepeatCardsByNominal
     */
    public function get(int $index): object
    {
        return parent::get($index);
    }
}
