<?php

declare(strict_types=1);

namespace App\SequraChallenge\Merchants\Domain\Entity;

use App\Shared\Domain\Aggregate\AggregateRoot;

class Merchant extends AggregateRoot
{

    public function __construct(
        private readonly MerchantId $id,
        private readonly MerchantReference $reference,
        private readonly MerchantEmail $email,
        private readonly MerchantLiveOn $liveOn,
        private readonly MerchantDisbursementFrequency $disbursementFrequency,
        private readonly MerchantMinimumMonthlyFee $minimumMonthlyFee
    )
    {
    }

    public static function create(
        string $id,
        string $reference,
        string $email,
        \DateTime $liveOn,
        string $disbursementFrequency,
        float $minimumMonthlyFee
    ): self {
        return new self(
            new MerchantId($id),
            new MerchantReference($reference),
            new MerchantEmail($email),
            new MerchantLiveOn($liveOn),
            MerchantDisbursementFrequency::from($disbursementFrequency),
            new MerchantMinimumMonthlyFee($minimumMonthlyFee)
        );
    }

    public function id(): MerchantId
    {
        return $this->id;
    }

    public function reference(): MerchantReference
    {
        return $this->reference;
    }

    public function email(): MerchantEmail
    {
        return $this->email;
    }

    public function liveOn(): MerchantLiveOn
    {
        return $this->liveOn;
    }

    public function disbursementFrequency(): MerchantDisbursementFrequency
    {
        return $this->disbursementFrequency;
    }

    public function minimumMonthlyFee(): MerchantMinimumMonthlyFee
    {
        return $this->minimumMonthlyFee;
    }


}