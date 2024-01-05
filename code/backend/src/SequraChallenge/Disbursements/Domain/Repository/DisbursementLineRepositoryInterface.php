<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Domain\Repository;

use App\SequraChallenge\DisbursementLines\Domain\Entity\DisbursementLine;

interface DisbursementLineRepositoryInterface
{
    public function save(DisbursementLine $disbursementLine): void;

    public function findByPurchaseId(string $purchaseId): ?DisbursementLine;
}
