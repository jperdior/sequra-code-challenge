<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\Entity;


use App\SequraChallenge\Domain\Entity\Enum\DisbursementFrequencyEnum;

class Merchant
{

    private string $id;

    private string $reference;

    private string $email;

    private \DateTime $liveOn;

    private int $disbursementFrequency = DisbursementFrequencyEnum::DAILY->value;

    private float $minimumMonthlyFee = 0;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): Merchant
    {
        $this->id = $id;
        return $this;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function setReference(string $reference): Merchant
    {
        $this->reference = $reference;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): Merchant
    {
        $this->email = $email;
        return $this;
    }

    public function getLiveOn(): \DateTime
    {
        return $this->liveOn;
    }

    public function setLiveOn(\DateTime $liveOn): Merchant
    {
        $this->liveOn = $liveOn;
        return $this;
    }

    public function getDisbursementFrequency(): int
    {
        return $this->disbursementFrequency;
    }

    public function setDisbursementFrequency(int $disbursementFrequency): Merchant
    {
        $this->disbursementFrequency = $disbursementFrequency;
        return $this;
    }

    public function getMinimumMonthlyFee(): float
    {
        return $this->minimumMonthlyFee;
    }

    public function setMinimumMonthlyFee(float $minimumMonthlyFee): Merchant
    {
        $this->minimumMonthlyFee = $minimumMonthlyFee;
        return $this;
    }

}

