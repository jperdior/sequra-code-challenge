<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\Entity;

class DisbursementLine
{
    private string $id;

    private Disbursement $disbursement;

    private string $purchaseId;

    private float $purchaseAmount;

    private float $amount;

    private float $feePercentage;

    private float $feeAmount;

    private \DateTime $createdAt;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): DisbursementLine
    {
        $this->id = $id;

        return $this;
    }

    public function getDisbursement(): Disbursement
    {
        return $this->disbursement;
    }

    public function setDisbursement(Disbursement $disbursement): DisbursementLine
    {
        $this->disbursement = $disbursement;

        return $this;
    }

    public function getPurchaseId(): string
    {
        return $this->purchaseId;
    }

    public function setPurchaseId(string $purchaseId): DisbursementLine
    {
        $this->purchaseId = $purchaseId;

        return $this;
    }

    public function getPurchaseAmount(): float
    {
        return $this->purchaseAmount;
    }

    public function setPurchaseAmount(float $purchaseAmount): DisbursementLine
    {
        $this->purchaseAmount = $purchaseAmount;

        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): DisbursementLine
    {
        $this->amount = $amount;

        return $this;
    }

    public function getFeePercentage(): float
    {
        return $this->feePercentage;
    }

    public function setFeePercentage(float $feePercentage): DisbursementLine
    {
        $this->feePercentage = $feePercentage;

        return $this;
    }

    public function getFeeAmount(): float
    {
        return $this->feeAmount;
    }

    public function setFeeAmount(float $feeAmount): DisbursementLine
    {
        $this->feeAmount = $feeAmount;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): DisbursementLine
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
