<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

abstract readonly class EmailValueObject
{
    public function __construct(public string $value)
    {
        $this->ensureIsValidEmail($value);
    }

    private function ensureIsValidEmail(string $value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException(sprintf('<%s> does not allow the value <%s>.', static::class, $value));
        }
    }
}
