<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\UseCase;

use App\SequraChallenge\Domain\Entity\Enum\PurchaseStatusEnum;
use App\SequraChallenge\Domain\Repository\PurchaseRepositoryInterface;
use App\SequraChallenge\Domain\Service\DisbursementCalculatorService;

class ProcessPurchaseUseCase
{
    public function __construct(
        private readonly DisbursementCalculatorService $disbursementCalculatorService,
        private readonly PurchaseRepositoryInterface $purchaseRepository,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function execute(
        string $purchaseId
    ): void {
        $purchase = $this->purchaseRepository->findById($purchaseId);
        if (null === $purchase) {
            throw new \Exception('Not found purchase with id: '.$purchaseId);
        }
        $this->disbursementCalculatorService->calculateDisbursement($purchase);
        $purchase->setStatus(PurchaseStatusEnum::PROCESSED->value);
        $this->purchaseRepository->save($purchase);
    }
}
