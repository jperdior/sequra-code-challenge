<?php

declare(strict_types=1);

namespace App\SequraChallenge\MerchantMonthlyFees\Domain\Entity;

use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;
use App\Shared\Domain\Aggregate\AggregateRoot;

class MerchantMonthlyFee extends AggregateRoot
{

    public function __construct(
        public readonly MerchantMonthlyFeeId    $id,
        public readonly MerchantReference       $merchantReference,
        private MerchantMonthlyFeeAmount        $amount,
        public readonly MerchantMonthlyFeeMonth $month,
    )
    {
    }

    final public static function create(
        string $id,
        string $merchantReference,
        float $amount,
        string $month
    ): MerchantMonthlyFee
    {
        $disbursementMonthlyFee = new self(
            new MerchantMonthlyFeeId($id),
            new MerchantReference($merchantReference),
            new MerchantMonthlyFeeAmount($amount),
            new MerchantMonthlyFeeMonth(new \DateTime($month))
        );

        return $disbursementMonthlyFee;
    }

    public function amount(): MerchantMonthlyFeeAmount
    {
        return $this->amount;
    }

    public function addAmount(float $newAmount): void
    {
        $this->amount = $this->amount->add($newAmount);
    }


}