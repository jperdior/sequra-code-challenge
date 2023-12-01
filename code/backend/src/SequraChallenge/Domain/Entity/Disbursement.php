<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\Entity;

class Disbursement
{

    private string $id;

    private string $reference;

    private Merchant $merchant;

    private float $fees = 0;

    private float $amount = 0;

    private float $monthlyFee = 0;

    private \DateTime $createdAt;

    private \DateTime $disbursedAt;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): Disbursement
    {
        $this->id = $id;
        return $this;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function setReference(string $reference): Disbursement
    {
        $this->reference = $reference;
        return $this;
    }

    public function getMerchant(): Merchant
    {
        return $this->merchant;
    }

    public function setMerchant(Merchant $merchant): Disbursement
    {
        $this->merchant = $merchant;
        return $this;
    }

    public function getFees(): float
    {
        return $this->fees;
    }

    public function setFees(float $fees): Disbursement
    {
        $this->fees = $fees;
        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): Disbursement
    {
        $this->amount = $amount;
        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): Disbursement
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getDisbursedAt(): \DateTime
    {
        return $this->disbursedAt;
    }

    public function setDisbursedAt(\DateTime $disbursedAt): Disbursement
    {
        $this->disbursedAt = $disbursedAt;
        return $this;
    }

    public function getMonthlyFee(): float
    {
        return $this->monthlyFee;
    }

    public function setMonthlyFee(float $monthlyFee): Disbursement
    {
        $this->monthlyFee = $monthlyFee;
        return $this;
    }


}