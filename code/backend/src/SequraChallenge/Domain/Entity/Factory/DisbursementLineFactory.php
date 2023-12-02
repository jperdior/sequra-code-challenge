<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\Entity\Factory;

use App\SequraChallenge\Domain\Entity\DisbursementLine;
use App\SequraChallenge\Domain\Entity\Purchase;
use App\SequraChallenge\Domain\Identifiers\UniqueIdGeneratorInterface;

class DisbursementLineFactory
{
    public function __construct(
        private UniqueIdGeneratorInterface $uniqueIdGenerator
    ) {
    }

    public function create(
        Purchase $purchase,
    ): DisbursementLine {
        $disbursementLine = new DisbursementLine();
        $disbursementLine->setId($this->uniqueIdGenerator->generateUlid());
        $disbursementLine->setPurchase($purchase);
        $disbursementLine->setPurchaseAmount($purchase->getAmount());
        $disbursementLine->setCreatedAt(new \DateTime());

        return $disbursementLine;
    }
}
