<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\UseCase;

use App\SequraChallenge\Domain\Entity\Disbursement;
use App\SequraChallenge\Domain\Entity\Enum\PurchaseStatusEnum;
use App\SequraChallenge\Domain\Repository\PurchaseRepositoryInterface;
use App\SequraChallenge\Domain\Service\DisbursementCalculatorService;

class ProcessOldestPurchaseUseCase
{

    public function __construct(
        private readonly DisbursementCalculatorService $disbursementCalculatorService,
        private readonly PurchaseRepositoryInterface $purchaseRepository,
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function execute(
    ): Disbursement
    {
        $purchase = $this->purchaseRepository->getOldestPendingPurchase();
        if (null === $purchase) {
            throw new \Exception('No pending purchases found');
        }
        $purchase->setStatus(PurchaseStatusEnum::PROCESSING->value);
        $disbursement = $this->disbursementCalculatorService->calculateDisbursement($purchase);
        $purchase->setStatus(PurchaseStatusEnum::PROCESSED->value);
        $this->purchaseRepository->save($purchase);
        return $disbursement;
    }

}