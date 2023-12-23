<?php

declare(strict_types=1);

namespace App\SequraChallenge\Purchases\Domain;

use App\SequraChallenge\Orders\Domain\DomainEvents\PurchaseCreatedDomainEvent;
use App\SequraChallenge\Purchases\Domain\ValueObjects\PurchaseAmount;
use App\SequraChallenge\Purchases\Domain\ValueObjects\PurchaseDate;
use App\SequraChallenge\Purchases\Domain\ValueObjects\PurchaseId;
use App\SequraChallenge\Purchases\Domain\ValueObjects\PurchaseStatus;
use App\Shared\Domain\Aggregate\AggregateRoot;

class Purchase extends AggregateRoot
{

    public function __construct(
        private readonly PurchaseId $id,
        private readonly PurchaseAmount $amount,
        private readonly PurchaseDate $createdAt,
        private readonly PurchaseStatus $status,
    ) {}

    public static function create(
        string $id,
        float $amount,
        \DateTime $createdAt
    ): self {

        $purchase = new self(
            new PurchaseId($id),
            new PurchaseAmount($amount),
            new PurchaseDate($createdAt),
            PurchaseStatus::PENDING
        );

        $purchase->record(
            new PurchaseCreatedDomainEvent(
                $id->value(),
                $amount->value(),
                $createdAt->value(),
                PurchaseStatus::PENDING->value
            )
        );

        return $purchase;
    }

    public function id(): PurchaseId
    {
        return $this->id;
    }

    public function amount(): PurchaseAmount
    {
        return $this->amount;
    }

    public function createdAt(): PurchaseDate
    {
        return $this->createdAt;
    }

    public function status(): PurchaseStatus
    {
        return $this->status;
    }

}