<?php

namespace App\Game\Actions;

use \App\Abstracts\AbstractGameAction;

class Fold extends AbstractGameAction
{
    protected string $name = 'Fold';
    protected int $id = 0;
    protected bool $payToBank = false;
}
