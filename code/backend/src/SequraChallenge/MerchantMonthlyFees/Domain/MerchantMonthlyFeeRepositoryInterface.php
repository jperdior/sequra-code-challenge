<?php

declare(strict_types=1);

namespace App\SequraChallenge\MerchantMonthlyFees\Domain;

use App\SequraChallenge\MerchantMonthlyFees\Domain\Entity\MerchantMonthlyFee;

interface MerchantMonthlyFeeRepositoryInterface
{

    public function save(MerchantMonthlyFee $disbursementMonthlyFee): void;

}
