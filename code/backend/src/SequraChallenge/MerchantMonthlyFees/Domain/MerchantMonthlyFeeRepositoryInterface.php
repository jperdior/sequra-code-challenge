<?php

declare(strict_types=1);

namespace App\SequraChallenge\MerchantMonthlyFees\Domain;

use App\SequraChallenge\MerchantMonthlyFees\Domain\Entity\MerchantMonthlyFee;
use App\SequraChallenge\MerchantMonthlyFees\Domain\Entity\MerchantMonthlyFeeFirstDayOfMonth;
use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;

interface MerchantMonthlyFeeRepositoryInterface
{

    public function save(MerchantMonthlyFee $disbursementMonthlyFee): void;

    public function search(MerchantReference $merchantReference, MerchantMonthlyFeeFirstDayOfMonth $firstDayOfMonth): ?MerchantMonthlyFee;

}
