<?php

namespace App\Game\Collections;

use App\Abstracts\AbstractGameCollection;
use App\Game\RepeatCardsBySuits;

class RepeatCardsBySuitCollection extends AbstractGameCollection
{
    /**
     * @param int $index
     * @return RepeatCardsBySuits
     */
    public function get(int $index): object
    {
        return parent::get($index);
    }
}
