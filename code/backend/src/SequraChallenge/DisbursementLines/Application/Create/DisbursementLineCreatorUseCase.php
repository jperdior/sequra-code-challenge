<?php

declare(strict_types=1);

namespace App\SequraChallenge\DisbursementLines\Application\Create;

use App\SequraChallenge\DisbursementLines\Domain\Entity\DisbursementLine;
use App\SequraChallenge\Disbursements\Domain\Repository\DisbursementLineRepositoryInterface;
use App\Shared\Domain\Bus\Event\EventBus;

final readonly class DisbursementLineCreatorUseCase
{

    public function __construct(
        private DisbursementLineRepositoryInterface $repository,
        private EventBus                            $eventBus
    )
    {}

    public function __invoke(
        string $disbursementReference,
        string $purchaseId,
        float $purchaseAmount,
    ): void
    {
        $disbursementLine = DisbursementLine::create(
            id: '',
            disbursementReference: $disbursementReference,
            purchaseId: $purchaseId,
            purchaseAmount: $purchaseAmount
        );

        $this->repository->save($disbursementLine);
        $this->eventBus->publish(...$disbursementLine->pullDomainEvents());
    }


}