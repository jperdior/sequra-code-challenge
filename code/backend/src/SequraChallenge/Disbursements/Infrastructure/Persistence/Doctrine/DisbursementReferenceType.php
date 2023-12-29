<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Infrastructure\Persistence\Doctrine;

use App\SequraChallenge\Shared\Domain\Disbursements\DisbursementReference;
use App\Shared\Infrastructure\Doctrine\UlidType;

final class DisbursementReferenceType extends UlidType
{
    protected function typeClassName(): string
    {
        return DisbursementReference::class;
    }
}

