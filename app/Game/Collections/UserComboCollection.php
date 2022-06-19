<?php

namespace App\Game\Collections;

use App\Abstracts\AbstractGameCollection;
use App\Game\UserCombo;

class UserComboCollection extends AbstractGameCollection
{
    /**
     * @param int $index
     * @return object|null
     */
    public function get(int $index): ?object
    {
        return parent::get($index);
    }
}
