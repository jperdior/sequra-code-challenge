<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\UseCase;

use App\SequraChallenge\Domain\Entity\Enum\PurchaseStatusEnum;
use App\SequraChallenge\Domain\Entity\Purchase;
use App\SequraChallenge\Domain\Repository\PurchaseRepositoryInterface;

readonly class MarkPurchaseProcessingUseCase
{
    public function __construct(
        private PurchaseRepositoryInterface $purchaseRepository
    ) {
    }

    public function execute(
        string $purchaseId
    ): Purchase {
        $purchase = $this->purchaseRepository->findById($purchaseId);
        $purchase->setStatus(PurchaseStatusEnum::PROCESSING->value);
        $this->purchaseRepository->save($purchase);

        return $purchase;
    }
}
