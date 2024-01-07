<?php

declare(strict_types=1);

namespace App\SequraChallenge\Merchants\Application\Find;

use App\SequraChallenge\Merchants\Domain\Entity\Merchant;

final class MerchantResponseConverter
{
    public function __invoke(Merchant $merchant): MerchantResponse
    {
        return new MerchantResponse(
            $merchant->reference()->value,
            $merchant->liveOn->value,
            $merchant->disbursementFrequency->value,
            $merchant->minimumMonthlyFee->value
        );
    }
}
