<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Domain\Events;

use App\Shared\Domain\Bus\Event\DomainEvent;

class DisbursementCalculatedEvent extends DomainEvent
{

    public function __construct(
        string $aggregateId,
        public readonly string $purchaseId,
        public readonly float $purchaseAmount,
        string $eventId = null,
        string $occurredOn = null
    )
    {
        parent::__construct($aggregateId, $eventId, $occurredOn);
    }

    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): DomainEvent
    {
        return new self(
            $aggregateId,
            $body['purchaseId'],
            $body['purchaseAmount'],
            $eventId,
            $occurredOn
        );
    }

    public static function eventName(): string
    {
        return 'disbursement.calculated';
    }

    public function toPrimitives(): array
    {
        return [
            'purchaseId' => $this->purchaseId,
            'purchaseAmount' => $this->purchaseAmount,
        ];
    }
}