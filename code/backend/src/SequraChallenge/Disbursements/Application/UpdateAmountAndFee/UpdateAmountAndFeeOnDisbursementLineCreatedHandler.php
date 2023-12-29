<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Application\UpdateAmountAndFee;

use App\SequraChallenge\DisbursementLines\Domain\DomainEvents\DisbursementLineCreatedDomainEvent;
use App\Shared\Domain\Bus\Event\DomainEventSubscriber;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

use function Lambdish\Phunctional\apply;

#[AsMessageHandler]
readonly class UpdateAmountAndFeeOnDisbursementLineCreatedHandler implements DomainEventSubscriber
{
    public function __construct(
        private UpdateAmountAndFeeUseCase $useCase,
    ) {
    }

    public function __invoke(DisbursementLineCreatedDomainEvent $event): void
    {
        apply($this->useCase, [
            $event->disbursementReference,
            $event->amount,
            $event->feeAmount,
        ]);
    }

    public static function subscribedTo(): array
    {
        return [DisbursementLineCreatedDomainEvent::class];
    }
}