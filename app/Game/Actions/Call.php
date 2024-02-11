<?php

namespace App\Game\Actions;

use \App\Abstracts\AbstractGameAction;

class Call extends AbstractGameAction
{
    protected string $name = 'Call';
    protected int $id = 4;
    protected bool $amountInMessage = false;
}
