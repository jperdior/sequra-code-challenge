<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Domain\Events;

use App\Shared\Domain\Bus\Event\DomainEvent;

class DisbursementAmountAndFeeIncreasedEvent extends DomainEvent
{
    public function __construct(
        string $aggregateId,
        public readonly string $merchantReference,
        public readonly float $amount,
        public readonly float $feeAmount,
        public readonly \DateTime $disbursedAt,
        string $eventId = null,
        string $occurredOn = null
    ) {
        parent::__construct($aggregateId, $eventId, $occurredOn);
    }

    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn,
    ): DomainEvent {
        return new self(
            $aggregateId,
            $body['merchantReference'],
            $body['amount'],
            $body['feeAmount'],
            new \DateTime($body['disbursedAt']),
            $eventId,
            $occurredOn
        );
    }

    public static function eventName(): string
    {
        return 'disbursement.increasedAmountAndFee';
    }

    public function toPrimitives(): array
    {
        return [
            'merchantReference' => $this->merchantReference,
            'amount' => $this->amount,
            'feeAmount' => $this->feeAmount,
            'disbursedAt' => $this->disbursedAt->format('Y-m-d H:i:s'),
        ];
    }
}
