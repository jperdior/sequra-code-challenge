<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\Entity;

use App\SequraChallenge\Domain\Entity\Enum\PurchaseStatusEnum;

class Purchase
{

    private string $id;

    private Merchant $merchant;

    private float $amount;

    private \DateTime $createdAt;

    private int $status = PurchaseStatusEnum::PENDING->value;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): Purchase
    {
        $this->id = $id;
        return $this;
    }


    public function getMerchant(): Merchant
    {
        return $this->merchant;
    }

    public function setMerchant(Merchant $merchant): Purchase
    {
        $this->merchant = $merchant;
        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): Purchase
    {
        $this->amount = $amount;
        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): Purchase
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): Purchase
    {
        $this->status = $status;
        return $this;
    }

    public function getDisbursement(): Disbursement
    {
        return $this->disbursement;
    }

    public function setDisbursement(Disbursement $disbursement): Purchase
    {
        $this->disbursement = $disbursement;
        return $this;
    }


}