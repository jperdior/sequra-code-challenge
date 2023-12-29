<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Application\FindOrCreate;

use App\SequraChallenge\Purchases\Domain\DomainEvents\PurchaseCreatedDomainEvent;
use App\Shared\Domain\Bus\Event\DomainEventSubscriber;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

use function Lambdish\Phunctional\apply;

#[AsMessageHandler]
readonly class FindOrCreateDisbursementOnPurchaseCreatedHandler implements DomainEventSubscriber
{
    public function __construct(
        private DisbursementFinderOrCreatorUseCase $disbursementCalculator,
    )
    {
    }

    public static function subscribedTo(): array
    {
        return [PurchaseCreatedDomainEvent::class];
    }

    public function __invoke(PurchaseCreatedDomainEvent $event): void
    {

        apply(
            $this->disbursementCalculator,
            [
                $event->merchantReference,
                $event->createdAt,
                $event->aggregateId(),
                $event->amount
            ]
        );

    }
}