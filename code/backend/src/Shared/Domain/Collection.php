<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use IteratorAggregate;

/** @template-implements IteratorAggregate<mixed>*/
abstract readonly class Collection implements \Countable, \IteratorAggregate
{
    public function __construct(private array $items)
    {
        Assert::arrayOf($this->type(), $items);
    }

    abstract protected function type(): string;

    final public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items());
    }

    final public function count(): int
    {
        return count($this->items());
    }

    protected function items(): array
    {
        return $this->items;
    }
}
