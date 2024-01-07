<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

abstract readonly class FloatValueObject
{
    public function __construct(public float $value)
    {
    }

    final public function isBiggerThan(self $other): bool
    {
        return $this->value > $other->value;
    }

    final public function rounded(int $precision = 0, int $mode = PHP_ROUND_HALF_UP): self
    {
        return new static(round($this->value, $precision, $mode));
    }

    final public function add(float $value): self
    {
        return new static($this->value + $value);
    }

    final public static function random(): self
    {
        return new static(rand(0, 100));
    }
}
