<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Domain;

use App\SequraChallenge\Disbursements\Domain\Entity\Disbursement;
use App\SequraChallenge\Disbursements\Domain\Entity\DisbursementDisbursedAt;
use App\SequraChallenge\Disbursements\Domain\Repository\DisbursementRepositoryInterface;
use App\SequraChallenge\Shared\Domain\Disbursements\DisbursementReference;
use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;

final readonly class DisbursementCalculator
{

    public function __construct(
        private DisbursementRepositoryInterface $repository,
    ) {
    }

    public function __invoke(
        MerchantReference $merchantReference,
        DisbursementDisbursedAt $disbursedAt
    ): Disbursement
    {
        $disbursement = $this->repository->getByMerchantAndDisbursedDate(
            merchantReference: $merchantReference,
            disbursedAt: $disbursedAt
        );

        if (null === $disbursement) {
            $disbursement = Disbursement::create(
                reference: DisbursementReference::random()->value,
                merchantReference: $merchantReference->value,
                disbursedAt: $disbursedAt->value,
            );
            $this->repository->save($disbursement);
        }

        return $disbursement;
    }

}