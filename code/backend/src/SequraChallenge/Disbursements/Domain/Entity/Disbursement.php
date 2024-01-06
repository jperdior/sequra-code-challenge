<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Domain\Entity;

use App\SequraChallenge\Disbursements\Domain\Events\DisbursementAmountAndFeeIncrasedEvent;
use App\SequraChallenge\Disbursements\Domain\Events\DisbursementAmountAndFeeIncreasedEvent;
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
        private DisbursementFee $fee,
        private DisbursementAmount $amount,
        private DisbursementMonthlyFee $monthlyFee,
        public readonly bool $firstOfMonth,
    ) {}

    public static function create(
        string $reference,
        string $merchantReference,
        \DateTime $disbursedAt,
        bool $firstOfMonth = false
    ): Disbursement
    {
        return new self(
            new DisbursementReference($reference),
            new MerchantReference($merchantReference),
            new DisbursementDisbursedAt($disbursedAt),
            new DisbursementFee(0),
            new DisbursementAmount(0),
            new DisbursementMonthlyFee(0),
            $firstOfMonth
        );
    }

    public function fee(): DisbursementFee
    {
        return $this->fee;
    }

    public function increaseFee(float $fee): void
    {
        $this->fee = $this->fee->add($fee);
    }

    public function amount(): DisbursementAmount
    {
        return $this->amount;
    }

    public function increaseAmount(float $amount): void
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

    public function increaseAmountAndFee(float $amount, float $fee): void
    {
        $this->increaseAmount($amount);
        $this->increaseFee($fee);

        $this->record(new DisbursementAmountAndFeeIncreasedEvent(
            $this->reference->value,
            $this->merchantReference->value,
            $this->amount->value,
            $this->fee->value,
            $this->disbursedAt->value
        ));
    }

}