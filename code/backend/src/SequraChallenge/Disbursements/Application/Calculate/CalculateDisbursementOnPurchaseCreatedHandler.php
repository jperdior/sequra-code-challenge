<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Application\Calculate;

use App\SequraChallenge\Purchases\Domain\Events\PurchaseCreatedEvent;
use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;
use App\Shared\Domain\Bus\Event\DomainEventSubscriber;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

use function Lambdish\Phunctional\apply;

#[AsMessageHandler]
readonly class CalculateDisbursementOnPurchaseCreatedHandler implements DomainEventSubscriber
{
    public function __construct(
        private CalculateDisbursementUseCase $disbursementCalculator,
    )
    {
    }

    public static function subscribedTo(): array
    {
        return [PurchaseCreatedEvent::class];
    }

    public function __invoke(PurchaseCreatedEvent $event): void
    {

        apply(
            $this->disbursementCalculator,
            [
                new MerchantReference($event->merchantReference),
                $event->createdAt,
                $event->aggregateId,
                $event->amount
            ]
        );

    }
}