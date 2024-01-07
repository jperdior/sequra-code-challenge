<?php

declare(strict_types=1);

namespace App\SequraChallenge\Merchants\Domain\Entity;

use App\Shared\Domain\ValueObject\EnumValueObject;

final readonly class MerchantDisbursementFrequency extends EnumValueObject
{
    public const DAILY = 'DAILY';
    public const WEEKLY = 'WEEKLY';

    public function ensureIsValidValue($value): void
    {
        if (!in_array($value, [self::DAILY, self::WEEKLY])) {
            throw new \InvalidArgumentException(sprintf('The disbursement frequency <%s> is invalid', $value));
        }
    }
}
