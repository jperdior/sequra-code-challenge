<?php

namespace App\SequraChallenge\MerchantMonthlyFees\Application\Find;

use App\SequraChallenge\MerchantMonthlyFees\Domain\Entity\MerchantMonthlyFee;

final readonly class MerchantMonthlyFeesResponseConverter
{
    public function __invoke(?MerchantMonthlyFee $merchantMonthlyFee): ?MerchantMonthlyFeesResponse
    {
        if (null == $merchantMonthlyFee) {
            return null;
        }

        return new MerchantMonthlyFeesResponse(
            $merchantMonthlyFee->id->value,
            $merchantMonthlyFee->firstDayOfMonth->value,
            $merchantMonthlyFee->feeAmount()->value,
        );
    }
}
