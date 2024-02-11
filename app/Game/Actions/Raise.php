<?php

namespace App\Game\Actions;

use App\Abstracts\AbstractGameAction;

class Raise extends AbstractGameAction
{
    protected string $name = 'Raise to ';
    protected int $id = 2;
    protected bool $amountInMessage = true;
}
