<?php

namespace App\Abstracts;

class AbstractGameCollection
{
    protected array $collection = [];

    public function push(object $obj): AbstractGameCollection
    {
        $this->collection[] = $obj;

        return $this;
    }

    public function remove(int $index): void
    {
        unset($this->collection[$index]);
    }

    public function get(int $index): object
    {
        $index = ($index === 1 && !isset($this->collection[1])) ? 13 : $index;

        return $this->collection[$index];
    }

    public function count(): int
    {
        return count($this->collection);
    }

    public function each(callable $func): void
    {
        foreach ($this->collection as $item){
            call_user_func($func,$item);
        }
    }

    public function keys(): array
    {
        return array_keys($this->collection);
    }

    public function getCollection(): array
    {
        return $this->collection;
    }

    public function empty()
    {
        $this->collection  = [];
    }
}
