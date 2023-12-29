<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Domain;

use App\SequraChallenge\Disbursements\Domain\Entity\Disbursement;
use App\SequraChallenge\Disbursements\Domain\Exception\DisbursementNotFound;
use App\SequraChallenge\Disbursements\Domain\Repository\DisbursementRepositoryInterface;
use App\SequraChallenge\Shared\Domain\Disbursements\DisbursementReference;

final readonly class DisbursementFinder
{

    public function __construct(
        private DisbursementRepositoryInterface $repository
    ) {
    }

    public function __invoke(DisbursementReference $reference): Disbursement
    {
        $disbursement = $this->repository->find($reference);
        if (null === $disbursement) {
            throw new DisbursementNotFound($reference);
        }
        return $disbursement;
    }

}