<?php

namespace App\Game\Collections;

use App\Abstracts\AbstractGameCollection;
use App\Game\Actions\AllIn;
use App\Game\Actions\Call;
use App\Game\Actions\Check;
use App\Game\Actions\Fold;
use App\Game\Actions\Raise;

class ActionCollection extends AbstractGameCollection
{
    public function __construct()
    {
        $this->collection = [
            new Fold(),
            new Check(),
            new Raise(),
            new AllIn(),
            new Call()
        ];
    }
}
