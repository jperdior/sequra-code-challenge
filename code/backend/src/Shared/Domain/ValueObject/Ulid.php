<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use Symfony\Component\Uid\Ulid as SymfonyUlid;

abstract readonly class Ulid implements \Stringable
{
    final public function __construct(public string $value)
    {
        $this->ensureIsValidUlid($value);
    }

    final public static function random(): self
    {
        return new static(SymfonyUlid::generate());
    }

    final public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private function ensureIsValidUlid(string $id): void
    {
        if (!SymfonyUlid::isValid($id)) {
            throw new \InvalidArgumentException(sprintf('<%s> does not allow the value <%s>.', self::class, $id));
        }
    }
}
