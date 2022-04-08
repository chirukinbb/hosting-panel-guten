<?php

namespace App\Game\Actions;

use App\Abstracts\AbstractGameAction;

class Raise extends AbstractGameAction
{
    protected string $name = 'Raise';
    protected int $id = 2;
    protected bool $payToBank = true;
}
