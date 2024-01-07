<?php

namespace App\SequraChallenge\DisbursementLines\Domain\Entity;

use App\Shared\Domain\ValueObject\EnumValueObject;

final readonly class DisbursementLineFeePercentage extends EnumValueObject
{
    private const SMALL_ORDER_AMOUNT_THRESHOLD = 50;
    private const MEDIUM_ORDER_AMOUNT_THRESHOLD = 300;

    private const SMALL_ORDER_PERCENTAGE = 100;
    private const MEDIUM_ORDER_PERCENTAGE = 95;
    private const LARGE_ORDER_PERCENTAGE = 85;

    public static function fromAmount(float $amount): self
    {
        if ($amount < self::SMALL_ORDER_AMOUNT_THRESHOLD) {
            return new self(self::SMALL_ORDER_PERCENTAGE);
        }
        if ($amount < self::MEDIUM_ORDER_AMOUNT_THRESHOLD) {
            return new self(self::MEDIUM_ORDER_PERCENTAGE);
        }

        return new self(self::LARGE_ORDER_PERCENTAGE);
    }

    public function ensureIsValidValue($value): void
    {
        if (!in_array($value, [self::SMALL_ORDER_PERCENTAGE, self::MEDIUM_ORDER_PERCENTAGE, self::LARGE_ORDER_PERCENTAGE])) {
            throw new \InvalidArgumentException('Invalid fee percentage');
        }
    }

    public function toFloat(): float
    {
        return $this->value / 100;
    }
}
