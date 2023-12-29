<?php

declare(strict_types=1);

namespace App\SequraChallenge\DisbursementLines\Domain\Entity;

use App\SequraChallenge\Shared\Domain\Disbursements\DisbursementReference;
use App\Shared\Domain\Aggregate\AggregateRoot;
use App\SequraChallenge\DisbursementLines\Domain\DomainEvents\DisbursementLineCreatedDomainEvent;

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
        float $purchaseAmount
    ): DisbursementLine
    {
        $feePercentage = DisbursementLineFeePercentage::fromAmount($purchaseAmount);
        $amount = new DisbursementLineAmount($purchaseAmount * ($feePercentage->value/100));
        $feeAmount = new DisbursementLineFeeAmount($purchaseAmount - $amount->value);

        $disbursementLine = new self(
            new DisbursementLineId($id),
            new DisbursementReference($disbursementReference),
            new DisbursementLinePurchaseId($purchaseId),
            new DisbursementLinePurchaseAmount($purchaseAmount),
            $amount->rounded(2),
            $feePercentage,
            $feeAmount->rounded(2)
        );

        $disbursementLine->record(new DisbursementLineCreatedDomainEvent(
            id: $disbursementLine->id->value,
            disbursementReference: $disbursementLine->disbursementReference->value,
            amount: $disbursementLine->amount->value,
            feeAmount: $disbursementLine->feeAmount->value
        ));

        return $disbursementLine;
    }

}