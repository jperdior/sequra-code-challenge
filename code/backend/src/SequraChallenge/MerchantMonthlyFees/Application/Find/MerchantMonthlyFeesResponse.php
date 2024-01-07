<?php

namespace App\SequraChallenge\MerchantMonthlyFees\Application\Find;

use App\Shared\Domain\Bus\Query\Response;

class MerchantMonthlyFeesResponse implements Response
{
    public function __construct(
        public string $merchantMonthlyFeeId,
        public \DateTime $firstDayOfMonth,
        public float $feeAmount,
    ) {
    }
}
