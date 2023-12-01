<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\Entity\Factory;

use App\SequraChallenge\Domain\Entity\DisbursementLine;
use App\SequraChallenge\Domain\Identifiers\UniqueIdGeneratorInterface;

class DisbursementLineFactory
{
    public function __construct(
        private UniqueIdGeneratorInterface $uniqueIdGenerator
    ) {
    }

    public function create(
        string $purchaseId,
        float $purchaseAmount,
    ): DisbursementLine {
        $disbursementLine = new DisbursementLine();
        $disbursementLine->setId($this->uniqueIdGenerator->generateUlid());
        $disbursementLine->setPurchaseId($purchaseId);
        $disbursementLine->setPurchaseAmount($purchaseAmount);
        $disbursementLine->setCreatedAt(new \DateTime());

        return $disbursementLine;
    }
}
