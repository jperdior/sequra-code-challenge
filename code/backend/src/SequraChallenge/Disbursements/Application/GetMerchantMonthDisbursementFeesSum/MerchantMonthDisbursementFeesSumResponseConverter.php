<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Application\GetMerchantMonthDisbursementFeesSum;

final class MerchantMonthDisbursementFeesSumResponseConverter
{
    public function __invoke(float $feesSum): MerchantMonthDisbursementFeesSumResponse
    {
        return new MerchantMonthDisbursementFeesSumResponse(
            $feesSum
        );
    }
}
