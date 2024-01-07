<?php

declare(strict_types=1);

namespace App\SequraChallenge\DisbursementLines\Application\Create;

use App\SequraChallenge\DisbursementLines\Domain\DisbursementLineRepositoryInterface;
use App\SequraChallenge\DisbursementLines\Domain\Entity\DisbursementLine;
use App\SequraChallenge\DisbursementLines\Domain\Entity\DisbursementLineId;
use App\Shared\Domain\Bus\Event\EventBus;

final readonly class CreateDisbursementLineUseCase
{
    public function __construct(
        private DisbursementLineRepositoryInterface $repository,
        private EventBus $eventBus
    ) {
    }

    public function __invoke(
        string $disbursementReference,
        string $purchaseId,
        float $purchaseAmount,
    ): void {
        $disbursementLineExists = $this->repository->findByPurchaseId($purchaseId);
        if (null !== $disbursementLineExists) {
            return;
        }

        $disbursementLine = DisbursementLine::create(
            id: DisbursementLineId::random()->value,
            disbursementReference: $disbursementReference,
            purchaseId: $purchaseId,
            purchaseAmount: $purchaseAmount
        );

        $this->repository->save($disbursementLine);
        $this->eventBus->publish(...$disbursementLine->pullDomainEvents());
    }
}
