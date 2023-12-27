<?php

declare(strict_types=1);

namespace App\SequraChallenge\Merchants\Domain\Entity;

use App\Shared\Domain\ValueObject\StringValueObject;

final readonly class MerchantId extends StringValueObject
{

    public function __toString(): string
    {
        return $this->value;
    }
}