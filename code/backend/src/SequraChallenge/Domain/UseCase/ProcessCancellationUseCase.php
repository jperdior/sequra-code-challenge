<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\UseCase;

use App\SequraChallenge\Domain\Exception\DisbursementLineNotFoundException;
use App\SequraChallenge\Domain\Exception\PurchaseNotFoundException;
use App\SequraChallenge\Domain\Repository\DisbursementLineRepositoryInterface;
use App\SequraChallenge\Domain\Repository\PurchaseRepositoryInterface;
use App\SequraChallenge\Domain\Service\DisbursementCalculatorService;

class ProcessCancellationUseCase
{
    public function __construct(
        private readonly DisbursementLineRepositoryInterface $disbursementLineRepository,
        private readonly PurchaseRepositoryInterface $purchaseRepository,
        private readonly DisbursementCalculatorService $disbursementCalculatorService,
    ) {
    }

    /**
     * @throws PurchaseNotFoundException
     * @throws DisbursementLineNotFoundException
     */
    public function execute(
        string $purchaseId,
        float $cancelledAmount
    ): void {
        $purchase = $this->purchaseRepository->findById($purchaseId);
        if (null === $purchase) {
            throw new PurchaseNotFoundException();
        }
        $disbursementLine = $this->disbursementLineRepository->findByPurchase($purchase);
        if (null === $disbursementLine) {
            throw new DisbursementLineNotFoundException();
        }
        $this->disbursementCalculatorService->calculateCancellation($disbursementLine, $cancelledAmount);
    }
}