<?php

declare(strict_types=1);

namespace App\SequraChallenge\DisbursementLines\Domain\Entity;

use App\SequraChallenge\Shared\Domain\Disbursements\DisbursementReference;
use App\Shared\Domain\Aggregate\AggregateRoot;

class DisbursementLine extends AggregateRoot
{

    public function __construct(
        public readonly DisbursementLineId             $id,
        public readonly DisbursementReference          $disbursementReference,
        public readonly DisbursementLinePurchaseId     $purchaseId,
        public readonly DisbursementLinePurchaseAmount $purchaseAmount,
        public readonly DisbursementLineAmount         $amount,
        public readonly DisbursementLineFeePercentage  $feePercentage,
        public readonly DisbursementLineFeeAmount      $feeAmount,
        public readonly \DateTime                      $createdAt = new \DateTime(),
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
            new DisbursementLineFeePercentage($feePercentage),
            new DisbursementLineFeeAmount($feeAmount),
            $createdAt
        );
    }

}