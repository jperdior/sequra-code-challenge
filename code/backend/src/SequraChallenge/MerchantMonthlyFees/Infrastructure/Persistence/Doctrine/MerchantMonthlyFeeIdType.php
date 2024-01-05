<?php

declare(strict_types=1);

namespace App\SequraChallenge\MerchantMonthlyFees\Infrastructure\Persistence\Doctrine;

use App\SequraChallenge\MerchantMonthlyFees\Domain\Entity\MerchantMonthlyFeeId;
use App\Shared\Infrastructure\Doctrine\UlidType;

final class MerchantMonthlyFeeIdType extends UlidType
{
    protected function typeClassName(): string
    {
        return MerchantMonthlyFeeId::class;
    }
}