<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Application\IncreaseAmountAndFee;

use App\SequraChallenge\DisbursementLines\Domain\Events\DisbursementLineCreatedEvent;
use App\Shared\Domain\Bus\Event\DomainEventSubscriber;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

use function Lambdish\Phunctional\apply;

#[AsMessageHandler]
readonly class IncreaseAmountAndFeeOnDisbursementLineCreatedHandler implements DomainEventSubscriber
{
    public function __construct(
        private IncreaseAmountAndFeeUseCase $useCase,
    ) {
    }

    public function __invoke(DisbursementLineCreatedEvent $event): void
    {
        apply($this->useCase, [
            $event->disbursementReference,
            $event->amount,
            $event->feeAmount,
        ]);
    }

    public static function subscribedTo(): array
    {
        return [DisbursementLineCreatedEvent::class];
    }
}