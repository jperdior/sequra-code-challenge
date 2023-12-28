<?php

declare(strict_types=1);

namespace App\SequraChallenge\Merchants\Domain\Entity;

use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;
use App\Shared\Domain\Aggregate\AggregateRoot;

class Merchant extends AggregateRoot
{

    public function __construct(
        private readonly MerchantReference $reference,
        private readonly MerchantEmail $email,
        public readonly MerchantLiveOn $liveOn,
        public readonly MerchantDisbursementFrequency $disbursementFrequency,
        public readonly MerchantMinimumMonthlyFee $minimumMonthlyFee
    )
    {
    }

    public static function create(
        string $reference,
        string $email,
        \DateTime $liveOn,
        string $disbursementFrequency,
        float $minimumMonthlyFee
    ): self {
        return new self(
            new MerchantReference($reference),
            new MerchantEmail($email),
            new MerchantLiveOn($liveOn),
            MerchantDisbursementFrequency::from($disbursementFrequency),
            new MerchantMinimumMonthlyFee($minimumMonthlyFee)
        );
    }

    public function reference(): MerchantReference
    {
        return $this->reference;
    }

    public function email(): MerchantEmail
    {
        return $this->email;
    }



}