<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Application\GetMerchantMonthDisbursementFeesSum;

use App\SequraChallenge\Disbursements\Domain\DisbursementRepositoryInterface;
use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;

final readonly class GetMerchantMonthDisbursementFeesSumUseCase
{

    public function __construct(
        private DisbursementRepositoryInterface $repository
    ) {
    }

    public function __invoke(MerchantReference $merchantReference, \DateTime $firstDayOfMonth): float
    {
        return $this->repository->getMerchantMonthDisbursementFeesSum($merchantReference, $firstDayOfMonth);
    }

}