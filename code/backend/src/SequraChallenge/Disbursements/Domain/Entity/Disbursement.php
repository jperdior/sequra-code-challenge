<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Domain\Entity;

use App\SequraChallenge\Disbursements\Domain\Events\DisbursementCalculatedEvent;
use App\SequraChallenge\Shared\Domain\Disbursements\DisbursementReference;
use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;
use App\Shared\Domain\Aggregate\AggregateRoot;

class Disbursement extends AggregateRoot
{

    public function __construct(
        public readonly DisbursementReference $reference,
        public readonly MerchantReference $merchantReference,
        public readonly DisbursementDisbursedAt $disbursedAt,
        private DisbursementFee $fee = new DisbursementFee(0),
        private DisbursementAmount $amount = new DisbursementAmount(0),
        private DisbursementMonthlyFee $monthlyFee = new DisbursementMonthlyFee(0),
        private readonly  \DateTime $createdAt = new \DateTime()
    ) {}

    public static function create(
        string $reference,
        string $merchantReference,
        \DateTime $disbursedAt
    ): Disbursement
    {
        return new self(
            new DisbursementReference($reference),
            new MerchantReference($merchantReference),
            new DisbursementDisbursedAt($disbursedAt)
        );
    }

    public function fee(): DisbursementFee
    {
        return $this->fee;
    }

    public function addFee(float $fee): void
    {
        $this->fee = $this->fee->add($fee);
    }

    public function amount(): DisbursementAmount
    {
        return $this->amount;
    }

    public function addAmount(float $amount): void
    {
        $this->amount = $this->amount->add($amount);
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

}