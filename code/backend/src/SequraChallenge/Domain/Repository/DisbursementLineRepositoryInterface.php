<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\Repository;

use App\SequraChallenge\Domain\Entity\Disbursement;
use App\SequraChallenge\Domain\Entity\DisbursementLine;
use App\SequraChallenge\Domain\Entity\Purchase;

interface DisbursementLineRepositoryInterface
{
    public function save(DisbursementLine $disbursementLine): void;

    public function existsByPurchase(string $purchaseId): bool;

    public function getAmountSumByDisbursement(Disbursement $disbursement): float;

    public function getFeeAmountSumByDisbursement(Disbursement $disbursement): float;

    public function findByPurchase(Purchase $purchase);
}
