<?php

namespace App\SequraChallenge\DisbursementLines\Domain\Entity;

enum DisbursementLineFeePercentage: int
{

    private const SMALL_ORDER_AMOUNT_THRESHOLD = 50;
    private const MEDIUM_ORDER_AMOUNT_THRESHOLD = 300;

    case SMALL_ORDER_PERCENTAGE = 100;
    case MEDIUM_ORDER_PERCENTAGE = 95;
    case LARGE_ORDER_PERCENTAGE = 85;

    public static function fromAmount(float $amount): self
    {
        if ($amount < self::SMALL_ORDER_AMOUNT_THRESHOLD) {
            return self::SMALL_ORDER_PERCENTAGE;
        }
        if ($amount < self::MEDIUM_ORDER_AMOUNT_THRESHOLD) {
            return self::MEDIUM_ORDER_PERCENTAGE;
        }
        return self::LARGE_ORDER_PERCENTAGE;
    }

}