<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\Entity\Enum;

enum DisbursementFrequencyEnum: int
{
    case DAILY = 1;
    case WEEKLY = 2;

    public static function getFromString(string $value): int
    {
        return match ($value) {
            'DAILY' => self::DAILY->value,
            'WEEKLY' => self::WEEKLY->value,
            default => throw new \InvalidArgumentException('Invalid disbursement frequency'),
        };
    }

}