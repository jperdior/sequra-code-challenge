<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

abstract readonly class StringValueObject
{
	public function __construct(public string $value) {}

}
