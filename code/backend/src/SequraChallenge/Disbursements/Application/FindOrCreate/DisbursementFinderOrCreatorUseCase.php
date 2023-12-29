<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Application\FindOrCreate;

use App\SequraChallenge\DisbursementLines\Application\Create\CreateDisbursementLineCommand;
use App\SequraChallenge\Disbursements\Domain\DisbursementDateCalculator;
use App\SequraChallenge\Disbursements\Domain\DisbursementFinderOrCreator;
use App\SequraChallenge\Disbursements\Domain\Repository\DisbursementRepositoryInterface;
use App\SequraChallenge\Merchants\Application\Find\FindMerchantQuery;
use App\SequraChallenge\Merchants\Domain\Entity\Merchant;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Bus\Query\QueryBus;

final readonly class DisbursementFinderOrCreatorUseCase
{
    public function __construct(
        private QueryBus                           $queryBus,
        private CommandBus                         $commandBus,
        private DisbursementDateCalculator         $disbursementDateCalculator,
        private DisbursementFinderOrCreator $disbursementFinderOrCreator,
        private DisbursementRepositoryInterface    $repository
    ) {
    }

    public function __invoke(
        string $merchantReference,
        \DateTime $createdAt,
        string $purchaseId,
        float $purchaseAmount
    ): void
    {
        /**
         * @var Merchant $merchant
         */
        $merchant = $this->queryBus->ask(new FindMerchantQuery($merchantReference));

        $disbursedAt = $this->disbursementDateCalculator->__invoke(
            merchantDisbursementFrequency: $merchant->disbursementFrequency->value,
            merchantLiveOnDate: $merchant->liveOn->value,
            purchaseCreatedAt: $createdAt
        );

        $disbursement = $this->disbursementFinderOrCreator->__invoke(
            merchantReference: $merchant->reference(),
            disbursedAt: $disbursedAt
        );

        // @todo: add monthly fee

        $this->repository->save($disbursement);

        $this->commandBus->dispatch(
            new CreateDisbursementLineCommand(
                disbursementReference: $disbursement->reference->value,
                purchaseId: $purchaseId,
                purchaseAmount: $purchaseAmount,
            )
        );

    }
}