<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Application\Calculate;

use App\SequraChallenge\Disbursements\Domain\DisbursementDateCalculator;
use App\SequraChallenge\Disbursements\Domain\DisbursementRepositoryInterface;
use App\SequraChallenge\Disbursements\Domain\Entity\Disbursement;
use App\SequraChallenge\Disbursements\Domain\Events\DisbursementCalculatedEvent;
use App\SequraChallenge\MerchantMonthlyFees\Application\Find\FindMerchantMonthlyFeesQuery;
use App\SequraChallenge\MerchantMonthlyFees\Application\Find\MerchantMonthlyFeesResponse;
use App\SequraChallenge\Merchants\Application\Find\FindMerchantQuery;
use App\SequraChallenge\Merchants\Application\Find\MerchantResponse;
use App\SequraChallenge\Shared\Domain\Disbursements\DisbursementReference;
use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;
use App\Shared\Domain\Bus\Event\EventBus;
use App\Shared\Domain\Bus\Query\QueryBus;

final readonly class CalculateDisbursementUseCase
{
    public function __construct(
        private EventBus $eventBus,
        private QueryBus $queryBus,
        private DisbursementDateCalculator $disbursementDateCalculator,
        private DisbursementRepositoryInterface $repository
    ) {
    }

    public function __invoke(
        MerchantReference $merchantReference,
        \DateTime $createdAt,
        string $purchaseId,
        float $purchaseAmount
    ): void {
        /**
         * @var MerchantResponse $merchant
         */
        $merchant = $this->queryBus->ask(new FindMerchantQuery($merchantReference->value));

        $disbursedAt = $this->disbursementDateCalculator->__invoke(
            merchantDisbursementFrequency: $merchant->disbursementFrequency,
            merchantLiveOnDate: $merchant->liveOn,
            purchaseCreatedAt: $createdAt
        );

        $disbursement = $this->repository->getByMerchantAndDisbursedDate(
            merchantReference: $merchantReference,
            disbursedAt: $disbursedAt
        );

        if (null === $disbursement) {
            $firstOfMonth = $this->repository->getFirstOfMonth(
                merchantReference: $merchantReference,
                disbursedAt: $disbursedAt
            );
            $disbursement = Disbursement::create(
                reference: DisbursementReference::random()->value,
                merchantReference: $merchantReference->value,
                disbursedAt: $disbursedAt->value,
                firstOfMonth: null === $firstOfMonth
            );
        }

        if (true === $disbursement->firstOfMonth) {
            $previousMonth = clone $disbursement->disbursedAt->value;
            /**
             * @var MerchantMonthlyFeesResponse $previousMonthMerchantMonthlyFees
             */
            $previousMonthMerchantMonthlyFees = $this->queryBus->ask(
                new FindMerchantMonthlyFeesQuery(
                    merchantReference: $merchantReference->value,
                    firstDayOfMonth: $previousMonth->modify('- 1 month')
                )
            );
            if (null !== $previousMonthMerchantMonthlyFees) {
                $disbursement->setMonthlyFee($merchant->minimumMonthlyFee - $previousMonthMerchantMonthlyFees->feeAmount);
            }
        }

        $this->repository->save($disbursement);

        $disbursementCalculatedEvent = new DisbursementCalculatedEvent(
            aggregateId: $disbursement->reference->value,
            purchaseId: $purchaseId,
            purchaseAmount: $purchaseAmount,
        );

        $this->eventBus->publish($disbursementCalculatedEvent);
    }
}
