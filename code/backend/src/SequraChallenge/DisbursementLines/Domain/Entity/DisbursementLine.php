<?php

declare(strict_types=1);

namespace App\SequraChallenge\DisbursementLines\Domain\Entity;

use App\SequraChallenge\Shared\Domain\Disbursements\DisbursementReference;

readonly class DisbursementLine
{

    public function __construct(
        public DisbursementLineId $id,
        public DisbursementReference $disbursementReference,
        public DisbursementLinePurchaseId $purchaseId,
        public DisbursementLinePurchaseAmount $purchaseAmount,
        public DisbursementLineAmount $amount,
        public DisbursementLinePercentage $feePercentage,
        public DisbursementLineFeeAmount $feeAmount,
        public \DateTime $createdAt = new \DateTime(),
    )
    {
    }

    public static function create(
        string $id,
        string $disbursementReference,
        string $purchaseId,
        float $purchaseAmount,
        float $amount,
        float $feePercentage,
        float $feeAmount,
        \DateTime $createdAt = new \DateTime(),
    ): DisbursementLine
    {
        return new self(
            new DisbursementLineId($id),
            new DisbursementReference($disbursementReference),
            new DisbursementLinePurchaseId($purchaseId),
            new DisbursementLinePurchaseAmount($purchaseAmount),
            new DisbursementLineAmount($amount),
            new DisbursementLinePercentage($feePercentage),
            new DisbursementLineFeeAmount($feeAmount),
            $createdAt
        );
    }

}