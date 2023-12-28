<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Domain;

use App\SequraChallenge\Disbursements\Domain\Entity\Disbursement;
use App\SequraChallenge\Disbursements\Domain\Entity\DisbursementDisbursedAt;
use App\SequraChallenge\Disbursements\Domain\Entity\DisbursementMerchantReference;
use App\SequraChallenge\Disbursements\Domain\Repository\DisbursementRepositoryInterface;
use App\SequraChallenge\Merchants\Domain\Entity\MerchantReference;

class DisbursementFinderByMerchantAndDate
{

    public function __construct(
        private DisbursementRepositoryInterface $repository
    ) {
    }

    public function __invoke(DisbursementMerchantReference $merchantReference, DisbursementDisbursedAt $disbursedAt): ?Disbursement
    {
        $disbursement = $this->repository->getByMerchantAndDisbursedDate($merchantReference, $disbursedAt);
        return $disbursement;
    }

}