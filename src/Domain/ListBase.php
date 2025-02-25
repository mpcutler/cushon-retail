<?php

declare(strict_types=1);

namespace App\Domain;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

abstract class ListBase implements Countable, IteratorAggregate
{
    protected $list = [];

    public function count(): int 
    {
        return count($this->list);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->list);
    }

    public function toArray(): array 
    {
        return array_slice($this->list, 0);
    }
}