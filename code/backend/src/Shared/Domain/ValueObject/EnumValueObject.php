<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use InvalidArgumentException;

abstract readonly class EnumValueObject
{

    public function __construct(public string $value)
    {
        $this->ensureIsValidValue($value);

    }

    private function ensureIsValidValue($value): void
    {
        $reflection = new \ReflectionClass(static::class);
        $constants = array_values($reflection->getConstants());

        if (!in_array($value, $constants)) {
            throw new InvalidArgumentException("Invalid value for enum ".static::class.": ".$value);
        }
    }

}