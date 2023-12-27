<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Domain\Entity;

use App\SequraChallenge\DisbursementLines\Domain\Entity\DisbursementLine;
use App\SequraChallenge\Domain\Entity\Merchant;
use App\Shared\Domain\Aggregate\AggregateRoot;

class Disbursement extends AggregateRoot
{

    public function __construct(
        public readonly DisbursementReference $reference,
        public readonly Merchant $merchant,
        public readonly DisbursementDisbursedAt $disbursedAt,
        private DisbursementFee $fee = new DisbursementFee(0),
        private DisbursementAmount $amount = new DisbursementAmount(0),
        private DisbursementMonthlyFee $monthlyFee = new DisbursementMonthlyFee(0),
        private readonly  \DateTime $createdAt = new \DateTime(),
        private DisbursementLines $disbursementLines = new DisbursementLines([])
    ) {}

    public static function create(
        string $reference,
        Merchant $merchant,
        \DateTime $disbursedAt
    ): Disbursement
    {
        return new self(
            new DisbursementReference($reference),
            $merchant,
            new DisbursementDisbursedAt($disbursedAt)
        );
    }

    public function fee(): DisbursementFee
    {
        return $this->fee;
    }

    private function addFee(float $fee): void
    {
        $this->fee = new DisbursementFee($this->fee->value + $fee);
    }

    public function amount(): DisbursementAmount
    {
        return $this->amount;
    }

    private function addAmount(float $amount): void
    {
        $this->amount = new DisbursementAmount($this->amount->value + $amount);
    }

    public function monthlyFee(): DisbursementMonthlyFee
    {
        return $this->monthlyFee;
    }

    public function setMonthlyFee(float $monthlyFee): void
    {
        $this->monthlyFee = new DisbursementMonthlyFee($monthlyFee);
    }

    public function createdAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function disbursementLines(): DisbursementLines
    {
        return $this->disbursementLines;
    }

    public function addDisbursementLine(
        string $id,
        string $purchaseId,
        float $purchaseAmount,
        float $amount,
        float $feePercentage,
        float $feeAmount
    ): void
    {
        $disbursementLine = DisbursementLine::create(
            id: $id,
            disbursement: $this,
            purchaseId: $purchaseId,
            purchaseAmount: $purchaseAmount,
            amount: $amount,
            feePercentage: $feePercentage,
            feeAmount: $feeAmount
        );

        $this->addFee($feeAmount);
        $this->addAmount($amount);

        $this->disbursementLines->add($disbursementLine);
    }

}