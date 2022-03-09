<?php

namespace App\Game\Collections;

use App\Abstracts\AbstractGameCollection;

class BankByStepsCollection extends AbstractGameCollection
{
    protected string $step;

    /**
     * @param string $step
     */
    public function setStep(string $step): void
    {
        $this->step = $step;
    }

    public function push(object $obj): BankByStepsCollection
    {
        if (! isset($this->collection[$this->step])) {
            $this->collection[$this->step] = new BankCollection();
            $this->collection[$this->step]->push($obj);
        }

        return $this;
    }
}
