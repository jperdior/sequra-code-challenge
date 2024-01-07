<?php

declare(strict_types=1);

namespace App\SequraChallenge\MerchantMonthlyFees\Application\Increment;

use App\SequraChallenge\DisbursementLines\Domain\Events\DisbursementLineCreatedEvent;
use App\SequraChallenge\Disbursements\Domain\Events\DisbursementAmountAndFeeIncreasedEvent;
use App\SequraChallenge\MerchantMonthlyFees\Domain\Entity\MerchantMonthlyFeeAmount;
use App\SequraChallenge\MerchantMonthlyFees\Domain\Entity\MerchantMonthlyFeeFirstDayOfMonth;
use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;
use App\Shared\Domain\Bus\Event\DomainEventSubscriber;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

use function Lambdish\Phunctional\apply;

#[AsMessageHandler]
final readonly class MerchantMonthlyFeeIncrementOnDisbursementAmountAndFeeIncreased implements DomainEventSubscriber
{

    public function __construct(
        private MerchantMonthlyFeeIncrementerUseCase $useCase,
    )
    {
    }

    public static function subscribedTo(): array
    {
        return [DisbursementLineCreatedEvent::class];
    }

    public function __invoke(DisbursementAmountAndFeeIncreasedEvent $event): void
    {
        apply(
            $this->useCase,
            [
                new MerchantReference($event->merchantReference),
                new MerchantMonthlyFeeFirstDayOfMonth($event->disbursedAt),
            ]
        );

    }

}