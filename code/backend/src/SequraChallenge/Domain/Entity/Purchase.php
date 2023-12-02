<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\Entity;

class Purchase
{
    private string $id;

    private Merchant $merchant;

    private float $amount;

    private \DateTime $createdAt;

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

}
