<?php

namespace App\Game;

class Turn
{
    public function __construct(public int $count,public string $channel)
    {
    }
}
