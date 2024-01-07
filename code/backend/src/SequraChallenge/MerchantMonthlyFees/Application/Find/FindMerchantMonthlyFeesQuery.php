<?php

declare(strict_types=1);

namespace App\SequraChallenge\MerchantMonthlyFees\Application\Find;

use App\Shared\Domain\Bus\Query\Query;

final readonly class FindMerchantMonthlyFeesQuery implements Query
{
    public function __construct(
        public string $merchantReference,
        public \DateTime $firstDayOfMonth,
    ) {
    }
}