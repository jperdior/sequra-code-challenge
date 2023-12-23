<?php

declare(strict_types=1);

namespace App\SequraChallenge\Purchases\Domain;

interface PurchaseRepositoryInterface
{
    public function getNotProcessed(int $limit): array;

    public function save(Purchase $purchase): void;

    public function findById(string $id): ?Purchase;

    public function markAsProcessed($purchaseIds);

    public function setStatus(array $purchaseIds, int $status);
}
