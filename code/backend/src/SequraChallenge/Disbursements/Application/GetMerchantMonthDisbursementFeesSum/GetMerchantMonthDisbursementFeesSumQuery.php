<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Application\GetMerchantMonthDisbursementFeesSum;

use App\Shared\Domain\Bus\Query\Query;

final readonly class GetMerchantMonthDisbursementFeesSumQuery implements Query
{
    public function __construct(
        public string $merchantReference,
        public \DateTime  $firstDayOfMonth
    ) {
    }
}