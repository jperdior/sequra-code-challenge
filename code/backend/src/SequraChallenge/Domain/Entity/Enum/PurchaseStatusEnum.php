<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\Entity\Enum;

enum PurchaseStatusEnum: int
{
    case PENDING = 1;
    case PROCESSING = 2;
    case PROCESSED = 3;
}
