<?php

declare(strict_types=1);

namespace App\SequraChallenge\DisbursementLines\Domain\Events;

use App\Shared\Domain\Bus\Event\DomainEvent;

class DisbursementLineCreatedEvent extends DomainEvent
{

    public function __construct(
        string $aggregateId,
        public readonly string $disbursementReference,
        public readonly float $amount,
        public readonly float $feeAmount,
        string $eventId = null,
        string $occurredOn = null
    )
    {
        parent::__construct($aggregateId, $eventId, $occurredOn);
    }


    public static function fromPrimitives(string $aggregateId, array $body, string $eventId, string $occurredOn): DomainEvent
    {
        return new self(
            $aggregateId,
            $body['disbursementReference'],
            $body['amount'],
            $body['feeAmount'],
            $eventId,
            $occurredOn
        );
    }

    public static function eventName(): string
    {
        return 'disbursement_line.created';
    }

    public function toPrimitives(): array
    {
        return [
            'disbursementReference' => $this->disbursementReference,
            'amount' => $this->amount,
            'feeAmount' => $this->feeAmount,
        ];
    }
}