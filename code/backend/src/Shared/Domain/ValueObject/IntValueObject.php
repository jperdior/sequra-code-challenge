<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

abstract readonly class IntValueObject
{
    public function __construct(public int $value)
    {
    }

    final public function isBiggerThan(self $other): bool
    {
        return $this->value > $other->value;
    }
}
