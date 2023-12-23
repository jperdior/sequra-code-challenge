<?php

declare(strict_types=1);

namespace App\SequraChallenge\Orders\Domain\DomainEvents;

use App\Shared\Domain\Bus\Event\DomainEvent;

final class PurchaseCreatedDomainEvent extends DomainEvent
{
    public function __construct(
        string $id,
        public readonly string $merchantId,
        public readonly float $amount,
        public readonly int $status,
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
            $body['merchantId'],
            $body['amount'],
            $body['status'],
            $eventId,
            $occurredOn
        );
    }

    public function toPrimitives(): array
    {
        return [
            'merchant_id' => $this->merchantId,
            'amount' => $this->amount,
            'status' => $this->status
        ];
    }
}
