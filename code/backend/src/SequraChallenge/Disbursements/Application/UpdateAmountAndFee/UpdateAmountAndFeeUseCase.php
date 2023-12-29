<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Application\UpdateAmountAndFee;

use App\SequraChallenge\Disbursements\Domain\DisbursementFinder;
use App\SequraChallenge\Disbursements\Domain\Repository\DisbursementRepositoryInterface;
use App\SequraChallenge\Shared\Domain\Disbursements\DisbursementReference;

final readonly class UpdateAmountAndFeeUseCase
{

    public function __construct(
        private DisbursementFinder              $disbursementFinder,
        private DisbursementRepositoryInterface $repository
    ) {
    }

    public function __invoke(DisbursementReference $reference, float $amount, float $fee): void
    {
        $disbursement = $this->disbursementFinder->__invoke($reference);
        $disbursement->addAmount($amount);
        $disbursement->addFee($fee);
        $this->repository->save($disbursement);
    }
}