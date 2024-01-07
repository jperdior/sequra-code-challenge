<?php

namespace App\SequraChallenge\Disbursements\Application\GetMerchantMonthDisbursementFeesSum;

use App\Shared\Domain\Bus\Query\Response;

final readonly class MerchantMonthDisbursementFeesSumResponse implements Response
{

    public function __construct(
        public float $feesSum
    ) {
    }

}