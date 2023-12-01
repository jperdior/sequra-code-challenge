<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\Repository;

use App\SequraChallenge\Domain\Entity\DisbursementLine;

interface DisbursementLineRepositoryInterface
{
    public function save(DisbursementLine $disbursementLine): void;

    public function existsByPurchase(string $purchaseId): bool;
}
