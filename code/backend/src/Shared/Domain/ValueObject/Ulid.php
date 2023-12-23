<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use InvalidArgumentException;
use Symfony\Component\Uid\Ulid as SymfonyUlid;
use Stringable;

abstract class Ulid implements Stringable
{
	final public function __construct(protected string $value)
	{
		$this->ensureIsValidUuid($value);
	}

	final public static function random(): self
	{
		return new static(SymfonyUlid::generate());
	}

	final public function value(): string
	{
		return $this->value;
	}

	final public function equals(self $other): bool
	{
		return $this->value() === $other->value();
	}

	public function __toString(): string
	{
		return $this->value();
	}

	private function ensureIsValidUuid(string $id): void
	{
		if (!SymfonyUlid::isValid($id)) {
			throw new InvalidArgumentException(sprintf('<%s> does not allow the value <%s>.', self::class, $id));
		}
	}
}
