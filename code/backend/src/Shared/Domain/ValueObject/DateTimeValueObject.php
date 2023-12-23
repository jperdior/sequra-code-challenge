<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

class DateTimeValueObject
{
    public function __construct(private \DateTime $value) {}

    public function value(): \DateTime
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value->format('Y-m-d H:i:s');
    }

    public function equals(self $other): bool
    {
        return $this->value() === $other->value();
    }

    public function format(string $format): string
    {
        return $this->value->format($format);
    }
}