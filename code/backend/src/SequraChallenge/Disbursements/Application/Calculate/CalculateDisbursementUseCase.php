<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Application\Calculate;

use App\SequraChallenge\DisbursementLines\Application\Create\CreateDisbursementLineCommand;
use App\SequraChallenge\Disbursements\Domain\DisbursementDateCalculator;
use App\SequraChallenge\Disbursements\Domain\DisbursementCalculator;
use App\SequraChallenge\Disbursements\Domain\Events\DisbursementCalculatedEvent;
use App\SequraChallenge\Merchants\Application\Find\MerchantResponse;
use App\SequraChallenge\Shared\Application\Merchants\Find\FindMerchantQuery;
use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Bus\Event\EventBus;
use App\Shared\Domain\Bus\Query\QueryBus;

final readonly class CalculateDisbursementUseCase
{
    public function __construct(
        private EventBus                         $eventBus,
        private QueryBus                           $queryBus,
        private DisbursementDateCalculator         $disbursementDateCalculator,
        private DisbursementCalculator             $disbursementCalculator
    ) {
    }

    public function __invoke(
        MerchantReference $merchantReference,
        \DateTime $createdAt,
        string $purchaseId,
        float $purchaseAmount
    ): void
    {
        /**
         * @var MerchantResponse $merchant
         */
        $merchant = $this->queryBus->ask(new FindMerchantQuery($merchantReference->value));

        $disbursedAt = $this->disbursementDateCalculator->__invoke(
            merchantDisbursementFrequency: $merchant->disbursementFrequency,
            merchantLiveOnDate: $merchant->liveOn,
            purchaseCreatedAt: $createdAt
        );

        $disbursement = $this->disbursementCalculator->__invoke(
            merchantReference: $merchantReference,
            disbursedAt: $disbursedAt
        );

        $disbursementCalculatedEvent = new DisbursementCalculatedEvent(
            aggregateId: $disbursement->reference->value,
            purchaseId: $purchaseId,
            purchaseAmount: $purchaseAmount,
        );

        $this->eventBus->publish($disbursementCalculatedEvent);


    }
}