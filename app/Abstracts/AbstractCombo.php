<?php

namespace App\Abstracts;

abstract class AbstractCombo
{
    protected string $name;
    protected array $userCardsPool;

    public function __construct()
    {
        $this->name =  $this->getName();
    }

    public function setUserCardsPool(array $cardsPool)
    {
        $this->userCardsPool = $cardsPool;
    }

    abstract public function getName():string;

    abstract public function check();
}
