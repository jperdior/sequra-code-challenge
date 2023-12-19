<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\Repository;

use App\SequraChallenge\Domain\Entity\Purchase;

interface PurchaseRepositoryInterface
{
    public function getNotProcessed(int $limit): array;

    public function save(Purchase $purchase): void;

    public function findById(string $id): ?Purchase;

    public function markAsProcessed($purchaseIds);

    public function setStatus(array $purchaseIds, int $status);
}
