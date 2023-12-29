<?php

declare(strict_types=1);

namespace App\SequraChallenge\DisbursementLines\Domain\DomainEvents;

use App\Shared\Domain\Bus\Event\DomainEvent;

class DisbursementLineCreatedDomainEvent extends DomainEvent
{

    public function __construct(
        string $id,
        public readonly string $disbursementReference,
        public readonly float $amount,
        public readonly float $feeAmount,
        string $eventId = null,
        string $occurredOn = null
    )
    {
        parent::__construct($id, $eventId, $occurredOn);
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