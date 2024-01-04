<?php

declare(strict_types=1);

namespace App\SequraChallenge\DisbursementLines\Application\Create;

use App\SequraChallenge\Disbursements\Domain\Events\DisbursementCalculatedEvent;
use App\SequraChallenge\Purchases\Domain\Events\PurchaseCreatedEvent;
use App\Shared\Domain\Bus\Event\DomainEventSubscriber;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

use function Lambdish\Phunctional\apply;

#[AsMessageHandler]
readonly class CreateDisbursementLineOnDisbursementCalculated implements DomainEventSubscriber
{
    public function __construct(
        private CreateDisbursementLineUseCase $useCase,
    ) {
    }

    public function __invoke(DisbursementCalculatedEvent $event): void
    {
        apply($this->useCase, [
            $event->aggregateId,
            $event->purchaseId,
            $event->purchaseAmount
        ]);
    }

    public static function subscribedTo(): array
    {
        return [PurchaseCreatedEvent::class];
    }
}