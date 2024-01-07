<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

abstract readonly class EnumValueObject
{
    public function __construct(public string|int $value)
    {
        $this->ensureIsValidValue($value);
    }

    abstract public function ensureIsValidValue($value): void;
}
