<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\Entity;

class CancellationLine
{

    private string $id;

    private float $amount;

    private DisbursementLine $disbursementLine;

    private \DateTime $createdAt;

    private Disbursement $disbursement;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): CancellationLine
    {
        $this->id = $id;
        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): CancellationLine
    {
        $this->amount = $amount;
        return $this;
    }

    public function getDisbursementLine(): DisbursementLine
    {
        return $this->disbursementLine;
    }

    public function setDisbursementLine(DisbursementLine $disbursementLine): CancellationLine
    {
        $this->disbursementLine = $disbursementLine;
        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): CancellationLine
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getDisbursement(): Disbursement
    {
        return $this->disbursement;
    }

    public function setDisbursement(Disbursement $disbursement): CancellationLine
    {
        $this->disbursement = $disbursement;
        return $this;
    }

}