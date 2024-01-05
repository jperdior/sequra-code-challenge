<?php

declare(strict_types=1);

namespace App\SequraChallenge\DisbursementLines\Infrastructure\Persistence\Doctrine;

use App\SequraChallenge\DisbursementLines\Domain\Entity\DisbursementLineId;
use App\Shared\Infrastructure\Doctrine\UlidType;

final class DisbursementLineIdType extends UlidType
{
    protected function typeClassName(): string
    {
        return DisbursementLineId::class;
    }
}