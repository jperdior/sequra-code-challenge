<?php

declare(strict_types=1);

namespace App\SequraChallenge\Shared\Domain\Merchants;

use App\Shared\Domain\ValueObject\StringValueObject;

final readonly class MerchantReference extends StringValueObject
{
    public function __toString(): string
    {
        return $this->value;
    }
}
