<?php

namespace App\SequraChallenge\MerchantMonthlyFees\Application\Find;

use App\SequraChallenge\MerchantMonthlyFees\Domain\Entity\MerchantMonthlyFee;
use App\SequraChallenge\MerchantMonthlyFees\Domain\Entity\MerchantMonthlyFeeFirstDayOfMonth;
use App\SequraChallenge\MerchantMonthlyFees\Domain\MerchantMonthlyFeeRepositoryInterface;
use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;

final readonly class MerchantMonthlyFeesFinderUseCase
{

    public function __construct(
        private MerchantMonthlyFeeRepositoryInterface $repository,
    ){
    }

    public function __invoke(MerchantReference $merchantReference, MerchantMonthlyFeeFirstDayOfMonth $firstDayOfMonth): ?MerchantMonthlyFee
    {
        return $this->repository->search($merchantReference, $firstDayOfMonth);
    }

}