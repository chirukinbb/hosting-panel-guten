<?php

namespace App\Game\Actions;

use App\Abstracts\AbstractGameAction;

class AllIn extends AbstractGameAction
{
    protected string $name = 'All-In';
    protected int $id = 2;
    protected bool $payToBank = true;
}
