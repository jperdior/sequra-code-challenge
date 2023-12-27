<?php

declare(strict_types=1);

namespace App\SequraChallenge\Purchases\Domain\DomainEvents;

use App\Shared\Domain\Bus\Event\DomainEvent;

final class PurchaseCreatedDomainEvent extends DomainEvent
{
    public function __construct(
        string $id,
        public readonly string $merchantReference,
        public readonly float $amount,
        public readonly \DateTime $createdAt,
        string $eventId = null,
        string $occurredOn = null
    ) {
        parent::__construct($id, $eventId, $occurredOn);
    }

    public static function eventName(): string
    {
        return 'purchase.created';
    }

    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): self {
        return new self(
            $aggregateId,
            $body['merchantReference'],
            $body['amount'],
            new \DateTime($body['createdAt']),
            $eventId,
            $occurredOn
        );
    }

    public function toPrimitives(): array
    {
        return [
            'merchant_reference' => $this->merchantReference,
            'amount' => $this->amount,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
        ];

    }
}
