<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

abstract readonly class DateTimeValueObject
{
    public function __construct(public \DateTime $value)
    {
    }

    public function __toString(): string
    {
        return $this->value->format('Y-m-d H:i:s');
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function format(string $format): string
    {
        return $this->value->format($format);
    }
}
