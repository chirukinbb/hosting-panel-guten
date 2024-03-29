<?php

namespace App\Game\Actions;

use App\Abstracts\AbstractGameAction;

class AllIn extends AbstractGameAction
{
    protected string $name = 'AllIn';
    protected int $id = 3;
    protected bool $amountInMessage = false;
}
