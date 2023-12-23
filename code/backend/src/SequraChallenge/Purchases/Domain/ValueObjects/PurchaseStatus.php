<?php

declare(strict_types=1);

namespace App\SequraChallenge\Purchases\Domain\ValueObjects;

enum PurchaseStatus: int
{
    case PENDING = 0;
    case PROCESSING = 1;
    case PROCESSED = 2;
}
