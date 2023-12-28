<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Application\Calculate;

use App\SequraChallenge\Disbursements\Domain\DisbursementDateCalculator;
use App\SequraChallenge\Merchants\Domain\Entity\Merchant;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\SequraChallenge\Merchants\Application\Find\FindMerchantQuery;
use App\SequraChallenge\Purchases\Domain\DomainEvents\PurchaseCreatedDomainEvent;
use App\Shared\Domain\Bus\Event\DomainEventSubscriber;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

use function Lambdish\Phunctional\apply;

#[AsMessageHandler]
class CalculateDisbursementOnPurchaseCreatedHandler implements DomainEventSubscriber
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly DisbursementDateCalculator $disbursementDateCalculator
    )
    {
    }

    public static function subscribedTo(): array
    {
        return [PurchaseCreatedDomainEvent::class];
    }

    public function __invoke(PurchaseCreatedDomainEvent $event): void
    {
        /**
         * @var Merchant $merchant
         */
        $merchant = $this->queryBus->ask(new FindMerchantQuery($event->merchantReference));

        $disbursementDate = $this->disbursementDateCalculator->__invoke(
            merchantDisbursementFrequency: $merchant->disbursementFrequency->value,
            merchantLiveOnDate: $merchant->liveOn->value,
            purchaseCreatedAt: $event->createdAt
        );

    }
}