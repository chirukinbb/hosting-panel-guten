<?php

namespace App\Game\Actions;

use App\Abstracts\AbstractGameAction;

class Check extends AbstractGameAction
{
    protected string $name = 'Check';
    protected int $id = 1;
    protected bool $payToBank  = false;
}
