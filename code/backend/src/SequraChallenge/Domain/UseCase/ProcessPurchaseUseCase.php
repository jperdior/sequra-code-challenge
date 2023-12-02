<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\UseCase;

use App\SequraChallenge\Domain\Exception\PurchaseNotFoundException;
use App\SequraChallenge\Domain\Repository\PurchaseRepositoryInterface;
use App\SequraChallenge\Domain\Service\DisbursementCalculatorService;

readonly class ProcessPurchaseUseCase
{
    public function __construct(
        private DisbursementCalculatorService $disbursementCalculatorService,
        private PurchaseRepositoryInterface   $purchaseRepository,
    ) {
    }

    /**
     * @throws PurchaseNotFoundException
     */
    public function execute(
        string $purchaseId
    ): void {
        $purchase = $this->purchaseRepository->findById($purchaseId);
        if (null === $purchase) {
            throw new PurchaseNotFoundException();
        }
        $this->disbursementCalculatorService->calculateDisbursement($purchase);
    }
}
