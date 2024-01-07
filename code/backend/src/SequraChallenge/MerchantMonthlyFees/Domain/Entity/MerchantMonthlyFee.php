<?php

declare(strict_types=1);

namespace App\SequraChallenge\MerchantMonthlyFees\Domain\Entity;

use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;
use App\Shared\Domain\Aggregate\AggregateRoot;

final class MerchantMonthlyFee extends AggregateRoot
{
    public function __construct(
        public readonly MerchantMonthlyFeeId $id,
        public readonly MerchantReference $merchantReference,
        public readonly MerchantMonthlyFeeFirstDayOfMonth $firstDayOfMonth,
        private MerchantMonthlyFeeAmount $feeAmount,
    ) {
    }

    final public static function create(
        string $id,
        string $merchantReference,
        \DateTime $date
    ): MerchantMonthlyFee {
        $disbursementMonthlyFee = new self(
            new MerchantMonthlyFeeId($id),
            new MerchantReference($merchantReference),
            new MerchantMonthlyFeeFirstDayOfMonth($date),
            new MerchantMonthlyFeeAmount(0)
        );

        return $disbursementMonthlyFee;
    }

    public function feeAmount(): MerchantMonthlyFeeAmount
    {
        return $this->feeAmount;
    }

    public function updateAmount(float $newAmount): void
    {
        $this->feeAmount = new MerchantMonthlyFeeAmount($newAmount);
    }
}
