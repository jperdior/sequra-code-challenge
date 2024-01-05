<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use InvalidArgumentException;

abstract readonly class EnumValueObject
{

    public function __construct(public string|int $value)
    {
        $this->ensureIsValidValue($value);

    }

    public abstract function ensureIsValidValue($value): void;

}